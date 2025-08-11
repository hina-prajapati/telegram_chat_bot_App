<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseQuestionController;

// class ProfessionController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $text = trim($text);

//          $forPreference = true;
//         $allowedProfessions = self::allowedProfessions($forPreference);

//         if (!in_array($text, $allowedProfessions, true)) {
//             return [
//                 'text' => __('messages.invalid_option'),
//                 'options' => self::getOptions(),
//                 'halt_flow' => true
//             ];
//         }

//         $answers = $state->answers;
//         $answers['profession'] = $text;

//         $this->saveAnswer($chatId, $state, 'profession', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_profession', ['profession' => $text]),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_profession');
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
//        $professions= DB::table('profession_categories')->pluck('name')->toArray();
//          $professions = array_map('trim', $professions);

//         if ($forPreference) {
//             $professions = array_filter($professions, fn($item) => strtolower($item) !== 'any');
//             $professions = array_values($professions); // reindex
//         } 

//         return $professions;
//     }
// }

class ProfessionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        $forPreference = true;
        $allowedProfessions = self::allowedProfessions($forPreference);

        if (!in_array($text, $allowedProfessions, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['profession'] = $text;

        $this->saveAnswer($chatId, $state, 'profession', $text, Profile::class);

        return [
            'text' => __('messages.thanks_profession', ['profession' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_profession');
    }

    public static function getOptions(): array
    {
        $professions = DB::table('profession_categories')->pluck('name')->toArray();
        $professions = array_map('trim', $professions);

        // Remove 'Any'
        $professions = array_filter($professions, fn($item) => strtolower($item) !== 'any');
        $professions = array_values($professions); // Reindex

        // Format into rows of 2 buttons each
        $keyboard = [];
        for ($i = 0; $i < count($professions); $i += 2) {
            $row = [['text' => $professions[$i]]];
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
            // Exclude only 'Any'
            $professions = array_filter($professions, fn($item) => strtolower($item) !== 'any');
            $professions = array_values($professions);
        }

        return $professions;
    }
}
