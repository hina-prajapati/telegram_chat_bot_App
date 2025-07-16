<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

class ReligionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.religion_hindu'),
            __('messages.religion_muslim'),
            __('messages.religion_christian'),
            __('messages.religion_sikh'),
            __('messages.religion_buddhist'),
            __('messages.religion_jain'),
            __('messages.religion_parsi'),
            __('messages.religion_jewish'),
            __('messages.religion_tribal'),
            __('messages.religion_none'),
            __('messages.other'),
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                 'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['religion'] = $text;

        $this->saveAnswer($chatId, $state, 'religion', $text, Profile::class);

        return [
            'text' => __('messages.thanks_religion', ['religion' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_religion');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.religion_hindu')], ['text' => __('messages.religion_muslim')]],
                    [['text' => __('messages.religion_christian')], ['text' => __('messages.religion_sikh')]],
                    [['text' => __('messages.religion_buddhist')], ['text' => __('messages.religion_jain')]],
                    [['text' => __('messages.religion_parsi')], ['text' => __('messages.religion_jewish')]],
                    [['text' => __('messages.religion_tribal')], ['text' => __('messages.religion_none')]],
                    [['text' => __('messages.other')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
