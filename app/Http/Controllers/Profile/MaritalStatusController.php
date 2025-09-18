<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class MaritalStatusController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['marital_status'] = $text;

//         $this->saveAnswer($chatId, $state, 'marital_status', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_marital_status', ['status' => $text]),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_marital_status');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.status_single')]],
//                     [['text' => __('messages.status_married')]],
//                     [['text' => __('messages.status_divorced')]],
//                     [['text' => __('messages.status_any')]],
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ]),
//             'parse_mode' => 'Markdown'
//         ];
//     }
// }


class MaritalStatusController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.status_single'),
            __('messages.status_married'),
            __('messages.status_divorced'),
            __('messages.status_widowed'),
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'marital_status', $text, Profile::class);

        return [
            'text' => __('messages.thanks_marital_status', ['status' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_marital_status');
    }

    public static function getOptions(): array
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.status_single')]],
                    [['text' => __('messages.status_married')]],
                    [['text' => __('messages.status_divorced')]],
                    [['text' => __('messages.status_widowed')]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]),
            'parse_mode' => 'Markdown'
        ];
    }
}
