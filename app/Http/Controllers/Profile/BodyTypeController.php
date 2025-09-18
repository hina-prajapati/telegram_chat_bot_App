<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

class BodyTypeController extends BaseQuestionController
{
    // public function handle($chatId, $text, TelegramUserState $state)
    // {
    //     $answers = $state->answers;
    //     $answers['body_type'] = $text;

    //     $this->saveAnswer($chatId, $state, 'body_type', $text, Profile::class);

    //     return [
    //         'text' => __('messages.body_type_saved', ['value' => $text]),
    //         'options' => ['parse_mode' => 'Markdown']
    //     ];
    // }

    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.body_type_slim'),
            __('messages.body_type_athletic'),
            __('messages.body_type_average'),
            __('messages.body_type_heavy'),
            __('messages.body_type_well_built'),
            __('messages.body_type_curvy'),
            __('messages.body_type_prefer_not_to_say'),
            __('messages.body_type_other'),
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => "❌ Please select a valid option from the buttons below.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // Save valid answer
        $this->saveAnswer($chatId, $state, 'body_type', $text, Profile::class);

        return [
            'text' => __('messages.body_type_saved', ['value' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_body_type');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.body_type_slim')], ['text' => __('messages.body_type_athletic')]],
                    [['text' => __('messages.body_type_average')], ['text' => __('messages.body_type_heavy')]],
                    [['text' => __('messages.body_type_well_built')], ['text' => __('messages.body_type_curvy')]],
                    [['text' => __('messages.body_type_prefer_not_to_say')], ['text' => __('messages.body_type_other')]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
