<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class MotherTongueController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['mother_tongue'] = $text;

//         $this->saveAnswer($chatId, $state, 'mother_tongue', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_mother_tongue', ['tongue' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

   
//     public static function getQuestion(): string
//     {
//         return __('messages.ask_mother_tongue');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.tongue_hindi')], ['text' => __('messages.tongue_marathi')]],
//                     [['text' => __('messages.tongue_gujarati')], ['text' => __('messages.tongue_punjabi')]],
//                     [['text' => __('messages.tongue_tamil')], ['text' => __('messages.tongue_telugu')]],
//                     [['text' => __('messages.tongue_bengali')], ['text' => __('messages.tongue_urdu')]],
//                     [['text' => __('messages.tongue_kannada')], ['text' => __('messages.tongue_malayalam')]],
//                     [['text' => __('messages.tongue_odia')], ['text' => __('messages.tongue_assamese')]],
//                     [['text' => __('messages.tongue_nepali')], ['text' => __('messages.tongue_sindhi')]],
//                     [['text' => __('messages.other')]]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
    
// }

class MotherTongueController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.tongue_hindi'),
            __('messages.tongue_marathi'),
            __('messages.tongue_gujarati'),
            __('messages.tongue_punjabi'),
            __('messages.tongue_tamil'),
            __('messages.tongue_telugu'),
            __('messages.tongue_bengali'),
            __('messages.tongue_urdu'),
            __('messages.tongue_kannada'),
            __('messages.tongue_malayalam'),
            __('messages.tongue_odia'),
            __('messages.tongue_assamese'),
            __('messages.tongue_nepali'),
            __('messages.tongue_sindhi'),
            __('messages.other')
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'mother_tongue', $text, Profile::class);

        return [
            'text' => __('messages.thanks_mother_tongue', ['tongue' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_mother_tongue');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.tongue_hindi')], ['text' => __('messages.tongue_marathi')]],
                    [['text' => __('messages.tongue_gujarati')], ['text' => __('messages.tongue_punjabi')]],
                    [['text' => __('messages.tongue_tamil')], ['text' => __('messages.tongue_telugu')]],
                    [['text' => __('messages.tongue_bengali')], ['text' => __('messages.tongue_urdu')]],
                    [['text' => __('messages.tongue_kannada')], ['text' => __('messages.tongue_malayalam')]],
                    [['text' => __('messages.tongue_odia')], ['text' => __('messages.tongue_assamese')]],
                    [['text' => __('messages.tongue_nepali')], ['text' => __('messages.tongue_sindhi')]],
                    [['text' => __('messages.other')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}

