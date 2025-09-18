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
        __('messages.religion_hinduism'),
        __('messages.religion_islam'),
        __('messages.religion_christianity'),
        __('messages.religion_sikhism'),
        __('messages.religion_buddhism'),
        __('messages.religion_jainism'),
        __('messages.religion_none'),
         __('messages.religion_other'),
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
        [['text' => __('messages.religion_hinduism')], ['text' => __('messages.religion_islam')]],
        [['text' => __('messages.religion_christianity')], ['text' => __('messages.religion_sikhism')]],
        [['text' => __('messages.religion_buddhism')], ['text' => __('messages.religion_jainism')]],
        [['text' => __('messages.religion_none')], ['text' => __('messages.religion_other')]],
    ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
