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
//         $answers = $state->answers ?? [];

//         // Step 1: Ask for Year
//         if (empty($answers['dob_year'])) {
//             $answers['dob_year'] = $text;
//             $state->answers = $answers;
//             $state->save();

//             return $this->askMonth(); // 👉 Ask for month next
//         }

//         // Step 2: Ask for Month
//         if (empty($answers['dob_month'])) {
//             // convert month name to number if needed
//             $months = $this->monthList();
//             $monthNumber = array_search(ucfirst(strtolower($text)), $months);
//             if (!$monthNumber) {
//                 return $this->askMonth("❌ Invalid month. Please select again.");
//             }

//             $answers['dob_month'] = $monthNumber;
//             $state->answers = $answers;
//             $state->save();

//             return $this->askDay(); // 👉 Ask for day next
//         }

//         // Step 3: Ask for Day
//         if (empty($answers['dob_day'])) {
//             $day = (int) $text;
//             if ($day < 1 || $day > 31) {
//                 return $this->askDay("❌ Invalid day. Please select again.");
//             }

//             $answers['dob_day'] = str_pad($day, 2, '0', STR_PAD_LEFT); // 01-31
//             $state->answers = $answers;
//             $state->save();

//             // ✅ Construct full date
//             $dob = "{$answers['dob_year']}-{$answers['dob_month']}-{$answers['dob_day']}";

//             try {
//                 $parsedDob = \Carbon\Carbon::parse($dob)->format('Y-m-d');
//             } catch (\Exception $e) {
//                 return $this->sendMessage($chatId, "❌ Invalid date. Please try again.");
//             }

//             // Save to DB
//             $this->saveAnswer($chatId, $state, 'dob', $parsedDob, Profile::class);

//             return [
//                 'text' => __('messages.thanks_dob', ['dob' => date('d-m-Y', strtotime($parsedDob))]),
//                 'options' => ['parse_mode' => 'Markdown']
//             ];
//         }

//         return $this->askYear(); // default fallback
//     }
//     public static function getQuestion(): string
//     {
//         return __('messages.ask_birth_year');
//     }
//     public static function getOptions(array $answers = []): array
//     {
//         return (new self())->askYear()['options']; // ✅ Correct
//     }
//     private function askYear(string $error = null): array
//     {
//         $years = range(date('Y') - 18, 1950);
//         $rows = array_chunk(array_map(fn($y) => ['text' => (string)$y], $years), 3);

//         return [
//             'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_year'),
//             'options' => [
//                 'parse_mode' => 'Markdown',
//                 'reply_markup' => json_encode([
//                     'keyboard' => $rows,
//                     'resize_keyboard' => true,
//                     'one_time_keyboard' => true
//                 ])
//             ]
//         ];
//     }
//     private function askMonth(string $error = null): array
//     {
//         $months = array_chunk(array_map(fn($m) => ['text' => $m], $this->monthList()), 3);

//         return [
//             'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_month'),
//             'options' => [
//                 'parse_mode' => 'Markdown',
//                 'reply_markup' => json_encode([
//                     'keyboard' => $months,
//                     'resize_keyboard' => true,
//                     'one_time_keyboard' => true
//                 ])
//             ]
//         ];
//     }
//     private function askDay(string $error = null): array
//     {
//         $days = range(1, 31);
//         $rows = array_chunk(array_map(fn($d) => ['text' => (string)$d], $days), 6);

//         return [
//             'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_day'),
//             'options' => [
//                 'parse_mode' => 'Markdown',
//                 'reply_markup' => json_encode([
//                     'keyboard' => $rows,
//                     'resize_keyboard' => true,
//                     'one_time_keyboard' => true
//                 ])
//             ]
//         ];
//     }
//     private function monthList(): array
//     {
//         return [
//             '01' => 'January',
//             '02' => 'February',
//             '03' => 'March',
//             '04' => 'April',
//             '05' => 'May',
//             '06' => 'June',
//             '07' => 'July',
//             '08' => 'August',
//             '09' => 'September',
//             '10' => 'October',
//             '11' => 'November',
//             '12' => 'December',
//         ];
//     }
// }
class DobController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers ?? [];

        // Step 1: Ask for Year
        if (empty($answers['dob_year'])) {
            if (!ctype_digit($text)) {
                return $this->askYear("❌ Invalid year. Please select a valid year from the keyboard.");
            }

            $year = (int)$text;
            $minYear = 1950;
            $maxYear = date('Y') - 18;

            if ($year < $minYear || $year > $maxYear) {
                return $this->askYear("❌ Invalid year. Please select a valid year from the keyboard.");
            }

            $answers['dob_year'] = $year;
            $state->answers = $answers;
            $state->save();

            return $this->askMonth();
        }

        // Step 2: Ask for Month
        if (empty($answers['dob_month'])) {
            $months = $this->monthList();

            // Case insensitive search for month name
            $monthNumber = false;
            foreach ($months as $num => $name) {
                if (strcasecmp($text, $name) === 0) {
                    $monthNumber = $num;
                    break;
                }
            }

            if ($monthNumber === false) {
                return $this->askMonth("❌ Invalid month. Please select again.");
            }

            $answers['dob_month'] = $monthNumber;
            $state->answers = $answers;
            $state->save();

            return $this->askDay();
        }

        // Step 3: Ask for Day
        if (empty($answers['dob_day'])) {
            if (!ctype_digit($text)) {
                return $this->askDay("❌ Invalid day. Please select again.");
            }

            $day = (int)$text;
            if ($day < 1 || $day > 31) {
                return $this->askDay("❌ Invalid day. Please select again.");
            }

            $answers['dob_day'] = str_pad($day, 2, '0', STR_PAD_LEFT);
            $state->answers = $answers;
            $state->save();

            // Construct full date string
            $dob = "{$answers['dob_year']}-{$answers['dob_month']}-{$answers['dob_day']}";

            try {
                $parsedDob = \Carbon\Carbon::parse($dob)->format('Y-m-d');
            } catch (\Exception $e) {
                return $this->sendMessage($chatId, "❌ Invalid date. Please try again.");
            }

            // Save DOB in profile
            $this->saveAnswer($chatId, $state, 'dob', $parsedDob, Profile::class);

            return [
                'text' => __('messages.thanks_dob', ['dob' => date('d-m-Y', strtotime($parsedDob))]),
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode(['remove_keyboard' => true])
                ],
                'halt_flow' => true
            ];
        }

        // If all done or fallback, restart flow
        // return $this->askYear();
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_birth_year');
    }

    public static function getOptions(array $answers = []): array
    {
        return (new self())->askYear()['options'];
    }

    private function askYear(string $error = null): array
    {
        $years = range(date('Y') - 18, 1950);
        $rows = array_chunk(array_map(fn($y) => ['text' => (string)$y], $years), 3);

        return [
            'text' => ($error ? $error . "\n\n" : '') . __('messages.ask_birth_year_val'),
            'options' => [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $rows,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ])
            ],
            'halt_flow' => true
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
            ],
            'halt_flow' => true
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
            ],
            'halt_flow' => true
        ];
    }

    private function monthList(): array
    {
        return [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }
}
