<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class SkinToneController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['skin_tone'] = $text;

//         $this->saveAnswer($chatId, $state, 'skin_tone', $text, Profile::class);

//         return [
//             'text' => __('messages.skin_tone_saved', ['value' => $text]) . "\n\n" . __('messages.ask_life_partner_intro'),
//             'options' => ['parse_mode' => 'Markdown']
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_skin_tone');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.skin_fair')], ['text' => __('messages.skin_wheatish')]],
//                     [['text' => __('messages.skin_dusky')], ['text' => __('messages.skin_dark')]]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class SkinToneController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $allowedValues = self::allowedSkinTones();
        $text = trim($text);

        if (!in_array($text, $allowedValues, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['skin_tone'] = $text;

        $this->saveAnswer($chatId, $state, 'skin_tone', $text, Profile::class);

        return [
            'text' => __('messages.skin_tone_saved', ['value' => $text]) . "\n\n" . __('messages.ask_life_partner_intro'),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_skin_tone');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.skin_fair')], ['text' => __('messages.skin_wheatish')]],
                    [['text' => __('messages.skin_wheatish_brown')], ['text' => __('messages.skin_dark')]],
                    [['text' => __('messages.skin_olive')], ['text' => __('messages.skin_brown')]],
                    [['text' => __('messages.skin_prefer_not_to_say')], ['text' => __('messages.skin_other')]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedSkinTones(): array
    {
        return [
            __('messages.skin_fair'),
            __('messages.skin_wheatish'),
            __('messages.skin_wheatish_brown'),
            __('messages.skin_dark'),
            __('messages.skin_olive'),
            __('messages.skin_brown'),
            __('messages.skin_prefer_not_to_say'),
            __('messages.skin_other'),
        ];
    }
}
