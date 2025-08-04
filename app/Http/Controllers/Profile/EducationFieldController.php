<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;


// class EducationFieldController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers ?? [];

//         if (($answers['education_field'] ?? null) === 'Other' && strtolower($text) !== 'other') {
//             $this->saveAnswer($chatId, $state, 'education_field', $text, Profile::class);

//             return [
//                 'text' => __('messages.thanks_education_field', ['field' => $text]),
//                 'options' => [
//                     'parse_mode' => 'Markdown'
//                 ]
//             ];
//         }

//         if (strtolower($text) === strtolower(__('messages.other'))) {
//             $answers['education_field'] = 'Other';
//             $state->update(['answers' => $answers]);

//             return [
//                 'text' => __('messages.type_education_field'),
//                 'options' => [
//                     'parse_mode' => 'Markdown',
//                     'reply_markup' => json_encode([
//                         'force_reply' => true,
//                         'input_field_placeholder' => 'Type your education field'
//                     ])
//                 ]
//             ];
//         }

//         $this->saveAnswer($chatId, $state, 'education_field', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_education_field', ['field' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_education_field');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.field_engineering')], ['text' => __('messages.field_arts')]],
//                     [['text' => __('messages.field_commerce')], ['text' => __('messages.field_science')]]
//                     [['text' => __('messages.other')]]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }
class EducationFieldController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.field_arts'),
            __('messages.field_science'),
            __('messages.field_commerce'),
            __('messages.field_engineering'),
            __('messages.field_medical'),
            __('messages.field_law'),
            __('messages.field_management'),
            __('messages.field_it'),
            __('messages.field_architecture'),
            __('messages.field_pharmacy'),
            __('messages.field_agriculture'),
            __('messages.field_media'),
            __('messages.field_fine_arts'),
            __('messages.field_education'),
            __('messages.field_vocational'),
            __('messages.field_designing'),
            __('messages.field_sports'),
            __('messages.other'),
        ];


        // Reject if input is not from allowed options
        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // Directly save even if user selected "Other" (no typing allowed)
        $this->saveAnswer($chatId, $state, 'education_field', $text, Profile::class);

        return [
            'text' => __('messages.thanks_education_field', ['field' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_education_field');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => __('messages.field_arts')],
                        ['text' => __('messages.field_science')],
                    ],
                    [
                        ['text' => __('messages.field_commerce')],
                        ['text' => __('messages.field_engineering')],
                    ],
                    [
                        ['text' => __('messages.field_medical')],
                        ['text' => __('messages.field_law')],
                    ],
                    [
                        ['text' => __('messages.field_management')],
                        ['text' => __('messages.field_it')],
                    ],
                    [
                        ['text' => __('messages.field_architecture')],
                        ['text' => __('messages.field_pharmacy')],
                    ],
                    [
                        ['text' => __('messages.field_agriculture')],
                        ['text' => __('messages.field_media')],
                    ],
                    [
                        ['text' => __('messages.field_fine_arts')],
                        ['text' => __('messages.field_education')],
                    ],
                    [
                        ['text' => __('messages.field_vocational')],
                        ['text' => __('messages.field_designing')],
                    ],
                    [
                        ['text' => __('messages.field_sports')],
                        ['text' => __('messages.other')],
                    ],
                ],

                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
