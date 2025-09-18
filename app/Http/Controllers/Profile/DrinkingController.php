<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class DrinkingController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['drinking'] = $text;

//         $this->saveAnswer($chatId, $state, 'drinking', $text, Profile::class);

//         return [
//             'text' => __('messages.drinking_saved', ['value' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.drinking_question');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.yes')], ['text' => __('messages.no')]],
//                     [['text' => __('messages.occasionally')]]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class DrinkingController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.yes'),
            __('messages.no'),
            __('messages.occasionally')
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'), // Add this to your lang file
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['drinking'] = $text;

        $this->saveAnswer($chatId, $state, 'drinking', $text, Profile::class);

        return [
            'text' => __('messages.drinking_saved', ['value' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.drinking_question');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.yes')], ['text' => __('messages.no')]],
                    [['text' => __('messages.occasionally')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
