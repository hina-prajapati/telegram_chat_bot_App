<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;
use Carbon\Carbon;

// class DobController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         try {
//             $dobFormatted = Carbon::createFromFormat('d-m-Y', $text)->format('Y-m-d');
//         } catch (\Exception $e) {
//             return [
//                 'text' => "❌ Invalid date format. Please enter in `DD-MM-YYYY` format, e.g., *13-07-1998*.",
//                 'options' => [
//                     'parse_mode' => 'Markdown',
//                     'reply_markup' => json_encode([
//                         'force_reply' => true,
//                         'input_field_placeholder' => 'DD-MM-YYYY'
//                     ])
//                 ]
//             ];
//         }

//         $answers = $state->answers;
//         $answers['dob'] = $dobFormatted;

//         $this->saveAnswer($chatId, $state, 'dob', $dobFormatted, Profile::class);

//         return [
//             'text' => __('messages.thanks_dob', ['dob' => date('d-m-Y', strtotime($dobFormatted))]),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }
  
//     public static function getQuestion(): string
//     {
//         return __('messages.ask_dob');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'force_reply' => true,
//                 'input_field_placeholder' => __('messages.dob_placeholder')
//             ])
//         ];
//     }
// }

class DobController extends BaseQuestionController
{
    // public function handle($chatId, $text, TelegramUserState $state)
    // {
    //     $answers = $state->answers ?? [];

    //     // Step 1: Year
    //     if (!isset($answers['dob_year'])) {
    //         if (!is_numeric($text) || strlen($text) != 4) {
    //             return $this->askYear("❌ Please select a valid birth year.");
    //         }

    //         $answers['dob_year'] = $text;
    //         $state->update(['answers' => $answers]);
    //         return $this->askMonth();
    //     }

    //     // Step 2: Month
    //     if (!isset($answers['dob_month'])) {
    //         $monthKey = array_search(ucfirst(strtolower($text)), $this->monthList());

    //         if (!$monthKey) {
    //             return $this->askMonth("❌ Invalid month. Please select again.");
    //         }

    //         $answers['dob_month'] = $monthKey;
    //         $state->update(['answers' => $answers]);
    //         return $this->askDay();
    //     }

    //     // Step 3: Day
    //     if (!isset($answers['dob_day'])) {
    //         if (!is_numeric($text) || $text < 1 || $text > 31) {
    //             return $this->askDay("❌ Invalid day. Please choose again.");
    //         }

    //         $answers['dob_day'] = str_pad($text, 2, '0', STR_PAD_LEFT);
    //         $state->update(['answers' => $answers]);

    //         $dob = "{$answers['dob_year']}-{$answers['dob_month']}-{$answers['dob_day']}";

    //         try {
    //             $formatted = Carbon::parse($dob)->format('Y-m-d');
    //         } catch (\Exception $e) {
    //             return $this->askYear("❌ Invalid date. Please start over.");
    //         }

    //         $this->saveAnswer($chatId, $state, 'dob', $formatted, Profile::class);

    //         return [
    //             'text' => __('messages.thanks_dob', ['dob' => Carbon::parse($formatted)->format('d-m-Y')]),
    //             'options' => ['parse_mode' => 'Markdown']
    //         ];
    //     }

    //     return $this->askYear(); // fallback
    // }

    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers ?? [];

        // Step 1: Ask for Year
        if (empty($answers['dob_year'])) {
            $answers['dob_year'] = $text;
            $state->answers = $answers;
            $state->save();

            return $this->askMonth(); // 👉 Ask for month next
        }

        // Step 2: Ask for Month
        if (empty($answers['dob_month'])) {
            // convert month name to number if needed
            $months = $this->monthList();
            $monthNumber = array_search(ucfirst(strtolower($text)), $months);
            if (!$monthNumber) {
                return $this->askMonth("❌ Invalid month. Please select again.");
            }

            $answers['dob_month'] = $monthNumber;
            $state->answers = $answers;
            $state->save();

            return $this->askDay(); // 👉 Ask for day next
        }

        // Step 3: Ask for Day
        if (empty($answers['dob_day'])) {
            $day = (int) $text;
            if ($day < 1 || $day > 31) {
                return $this->askDay("❌ Invalid day. Please select again.");
            }

            $answers['dob_day'] = str_pad($day, 2, '0', STR_PAD_LEFT); // 01-31
            $state->answers = $answers;
            $state->save();

            // ✅ Construct full date
            $dob = "{$answers['dob_year']}-{$answers['dob_month']}-{$answers['dob_day']}";

            try {
                $parsedDob = \Carbon\Carbon::parse($dob)->format('Y-m-d');
            } catch (\Exception $e) {
                return $this->sendMessage($chatId, "❌ Invalid date. Please try again.");
            }

            // Save to DB
            $this->saveAnswer($chatId, $state, 'dob', $parsedDob, Profile::class);

            return [
                'text' => __('messages.thanks_dob', ['dob' => date('d-m-Y', strtotime($parsedDob))]),
                'options' => ['parse_mode' => 'Markdown']
            ];
        }

        return $this->askYear(); // default fallback
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_birth_year');
    }

    public static function getOptions(array $answers = []): array
    {
        return (new self())->askYear()['options']; // ✅ Correct
    }

    private function askYear(string $error = null): array
    {
        $years = range(date('Y') - 18, 1950);
        $rows = array_chunk(array_map(fn($y) => ['text' => (string)$y], $years), 3);

        return [
            'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_year'),
            'options' => [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $rows,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ])
            ]
        ];
    }

    private function askMonth(string $error = null): array
    {
        $months = array_chunk(array_map(fn($m) => ['text' => $m], $this->monthList()), 3);

        return [
            'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_month'),
            'options' => [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $months,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ])
            ]
        ];
    }

    private function askDay(string $error = null): array
    {
        $days = range(1, 31);
        $rows = array_chunk(array_map(fn($d) => ['text' => (string)$d], $days), 6);

        return [
            'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_day'),
            'options' => [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $rows,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ])
            ]
        ];
    }

    private function monthList(): array
    {
        return [
            '01' => 'January', '02' => 'February', '03' => 'March',
            '04' => 'April',   '05' => 'May',      '06' => 'June',
            '07' => 'July',    '08' => 'August',   '09' => 'September',
            '10' => 'October', '11' => 'November', '12' => 'December',
        ];
    }
}

