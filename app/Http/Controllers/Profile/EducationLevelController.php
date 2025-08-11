<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class EducationLevelController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['education_level'] = $text;

//         $this->saveAnswer($chatId, $state, 'education_level', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_education', ['education' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_education');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [
//                         ['text' => __('messages.education_highschool')],
//                         ['text' => __('messages.education_diploma')]
//                     ],
//                     [
//                         ['text' => __('messages.education_bachelor')],
//                         ['text' => __('messages.education_master')]
//                     ],
//                     [
//                         ['text' => __('messages.education_phd')],
//                         ['text' => __('messages.other')]
//                     ],
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class EducationLevelController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.education_highschool'),
            __('messages.education_diploma'),
            __('messages.education_bachelor'),
            __('messages.education_master'),
            __('messages.education_phd'),
            __('messages.education_postdoc'),
            __('messages.education_professional'),
            __('messages.education_incomplete'),
            __('messages.education_prefer_not_say'),
            __('messages.education_other'),
        ];


        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'education_level', $text, Profile::class);

        return [
            'text' => __('messages.thanks_education', ['education' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_education');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => __('messages.education_highschool')],
                        ['text' => __('messages.education_diploma')],
                    ],
                    [
                        ['text' => __('messages.education_bachelor')],
                        ['text' => __('messages.education_master')],
                    ],
                    [
                        ['text' => __('messages.education_phd')],
                        ['text' => __('messages.education_postdoc')],
                    ],
                    [
                        ['text' => __('messages.education_professional')],
                        ['text' => __('messages.education_incomplete')],
                    ],
                    [
                        ['text' => __('messages.education_prefer_not_say')],
                        ['text' => __('messages.education_other')],
                    ],
                ],

                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
