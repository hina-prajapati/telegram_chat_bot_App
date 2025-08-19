<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

// class BirthTimeController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $text = trim($text);

//         // Simple validation: format HH:MM (24h)
//         if (!preg_match('/^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$/', $text)) {
//             return [
//                 'text' => "❌ Invalid time format. Please enter in HH:MM (24-hour) format.\n\n" . self::getQuestion(),
//                 'options' => self::getOptions(),
//                 'halt_flow' => true
//             ];
//         }

//         $answers = $state->answers;
//         $answers['birth_time'] = $text;

//         $this->saveAnswer($chatId, $state, 'birth_time', $text, Profile::class);

//         return [
//             'text' => "⏰ Birth time saved: *{$text}*",
//             'options' => []
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return "🕒 What is your birth time? (e.g., 13:45)";
//     }

//     public static function getOptions(array $answers = []): array
//     {
//         return [
//             'reply_markup' => json_encode([
//                 'force_reply' => true,
//                 'input_field_placeholder' => 'e.g., 14:30'
//             ])
//         ];
//     }
// }

class BirthTimeController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        // ⏱️ Updated validation for HH:MM:SS (24h)
        if (!preg_match('/^(2[0-3]|[01]?[0-9]):([0-5][0-9]):([0-5][0-9])$/', $text)) {
            return [
                'text' => "❌ Invalid time format. Please enter in HH:MM:SS (24-hour) format.\n\n" . self::getQuestion(),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['birth_time'] = $text;

        $this->saveAnswer($chatId, $state, 'birth_time', $text, Profile::class);

        return [
            'text' => "⏰ Birth time saved: *{$text}*",
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return "🕒 What is your birth time? (e.g., 13:45:30)";
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => 'e.g., 14:30:00'
            ])
        ];
    }
}

