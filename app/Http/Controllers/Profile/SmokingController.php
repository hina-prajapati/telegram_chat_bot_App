<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class SmokingController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['smoking'] = $text;

//         $this->saveAnswer($chatId, $state, 'smoking', $text, Profile::class);
  
//         return [
//             'text' => __('messages.saved_smoking', ['value' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_smoke');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.option_yes')], ['text' => __('messages.option_no')]],
//                     [['text' => __('messages.option_occasionally')]]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class SmokingController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $allowedOptions = self::allowedSmokingOptions();
        $text = trim($text);

        if (!in_array($text, $allowedOptions, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['smoking'] = $text;

        $this->saveAnswer($chatId, $state, 'smoking', $text, Profile::class);
  
        return [
            'text' => __('messages.saved_smoking', ['value' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_smoke');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.option_yes')], ['text' => __('messages.option_no')]],
                    [['text' => __('messages.option_occasionally')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedSmokingOptions(): array
    {
        return [
            __('messages.option_yes'),
            __('messages.option_no'),
            __('messages.option_occasionally')
        ];
    }
}

