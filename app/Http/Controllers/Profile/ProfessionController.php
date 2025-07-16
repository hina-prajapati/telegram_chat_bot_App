<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class ProfessionController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         // 'halt_flow' => true
//         $answers = $state->answers;
//         $answers['profession'] = $text;

//         $this->saveAnswer($chatId, $state, 'profession', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_profession', ['profession' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_profession');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.profession_software_engineer')], ['text' => __('messages.profession_doctor')]],
//                     [['text' => __('messages.profession_teacher')], ['text' => __('messages.profession_businessman')]],
//                     [['text' => __('messages.profession_student')], ['text' => __('messages.profession_house_maker')]],
//                     [['text' => __('messages.profession_other')]],
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
        
//     }
// }

class ProfessionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);
        $allowedProfessions = self::allowedProfessions();

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
        $keyboard = [
            [['text' => __('messages.profession_software_engineer')], ['text' => __('messages.profession_doctor')]],
            [['text' => __('messages.profession_teacher')], ['text' => __('messages.profession_businessman')]],
            [['text' => __('messages.profession_student')], ['text' => __('messages.profession_house_maker')]],
            [['text' => __('messages.profession_other')]],
        ];

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedProfessions(): array
    {
        return [
            __('messages.profession_software_engineer'),
            __('messages.profession_doctor'),
            __('messages.profession_teacher'),
            __('messages.profession_businessman'),
            __('messages.profession_student'),
            __('messages.profession_house_maker'),
            __('messages.profession_other'),
        ];
    }
}
