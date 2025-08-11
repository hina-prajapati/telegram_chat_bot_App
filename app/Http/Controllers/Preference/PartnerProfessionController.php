<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseQuestionController;

// class PartnerProfessionController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $text = trim($text);
//         $allowedProfessions = self::allowedProfessions();

//         if (!in_array($text, $allowedProfessions, true)) {
//             return [
//                 'text' => __('messages.partner_profession_invalid_option'),
//                 'options' => self::getOptions(),
//                 'halt_flow' => true
//             ];
//         }

//         $answers = $state->answers;
//         $answers['partner_profession'] = $text;

//         $this->saveAnswer($chatId, $state, 'partner_profession', $text, Preference::class);

//         return [
//             'text' => __('messages.thanks_partner_profession', ['partner_profession' => $text]),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_partner_profession');
//     }

//     public static function getOptions(): array
//     {
//         $professions = DB::table('profession_categories')->pluck('name')->toArray();

//         // Format into rows of 2 buttons each
//         $keyboard = [];
//         for ($i = 0; $i < count($professions); $i += 2) {
//             $row = [];
//             $row[] = ['text' => $professions[$i]];
//             if (isset($professions[$i + 1])) {
//                 $row[] = ['text' => $professions[$i + 1]];
//             }
//             $keyboard[] = $row;
//         }

//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => $keyboard,
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }

//     private static function allowedProfessions($forPreference = false): array
//     {
//         $professions = DB::table('profession_categories')->pluck('name')->toArray();

//         // Trim whitespace on all professions
//         $professions = array_map('trim', $professions);

//         $professions = array_values($professions);

//         array_push($professions, 'Any');
//         return $professions;
//     }
// }


class PartnerProfessionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        // Detect that this is a preference flow
        $forPreference = true;

        $allowedProfessions = self::allowedProfessions($forPreference);

        if (!in_array($text, $allowedProfessions, true)) {
            return [
                'text' => __('messages.partner_profession_invalid_option'),
                'options' => self::getOptions($forPreference),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['partner_profession'] = $text;
        $state->answers = $answers;

        $this->saveAnswer($chatId, $state, 'partner_profession', $text, Preference::class);

        return [
            'text' => __('messages.thanks_partner_profession', ['partner_profession' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_profession');
    }

    public static function getOptions($forPreference = false): array
    {
        $professions = self::allowedProfessions($forPreference);

        // Format into rows of 2 buttons each
        $keyboard = [];
        for ($i = 0; $i < count($professions); $i += 2) {
            $row = [];
            $row[] = ['text' => $professions[$i]];
            if (isset($professions[$i + 1])) {
                $row[] = ['text' => $professions[$i + 1]];
            }
            $keyboard[] = $row;
        }

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedProfessions($forPreference = false): array
    {
        $professions = DB::table('profession_categories')->pluck('name')->toArray();

        $professions = array_map('trim', $professions);

        if ($forPreference) {
            $professions = array_filter($professions, fn($item) => strtolower($item) !== 'other');
            $professions = array_values($professions); // reindex
        } 

        return $professions;
    }
}
