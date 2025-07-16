<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

// class PartnerMaritalStatusController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['partner_marital_status'] = $text;

//         $this->saveAnswer($chatId, $state, 'partner_marital_status', $text, Preference::class);

//         return [
//             'text' => __('messages.partner_marital_status_saved', ['value' => $text]),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }


//     public static function getQuestion(): string
//     {
//         return __('messages.ask_partner_marital_status');
//     }

//     public static function getOptions(array $answers = []): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [
//                         ['text' => __('messages.status_single')],
//                         ['text' => __('messages.status_divorced')]
//                     ],
//                     [
//                         ['text' => __('messages.status_widowed')],
//                         ['text' => __('messages.status_any')]
//                     ]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class PartnerMaritalStatusController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $allowedOptions = self::allowedOptions();
        $text = trim($text);

        if (!in_array($text, $allowedOptions, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['partner_marital_status'] = $text;

        $this->saveAnswer($chatId, $state, 'partner_marital_status', $text, Preference::class);

        return [
            'text' => __('messages.partner_marital_status_saved', ['value' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_marital_status');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => __('messages.status_single')],
                        ['text' => __('messages.status_divorced')]
                    ],
                    [
                        ['text' => __('messages.status_widowed')],
                        ['text' => __('messages.status_any')]
                    ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedOptions(): array
    {
        return [
            __('messages.status_single'),
            __('messages.status_divorced'),
            __('messages.status_widowed'),
            __('messages.status_any')
        ];
    }
}

