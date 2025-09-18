<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

class PartnerReligionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.partner_religion_hindu'),
            __('messages.partner_religion_muslim'),
            __('messages.partner_religion_christian'),
            __('messages.partner_religion_sikh'),
            __('messages.partner_religion_buddhist'),
            __('messages.partner_religion_jain'),
            __('messages.partner_religion_parsi'),
            __('messages.partner_religion_jewish'),
            __('messages.partner_religion_tribal'),
            __('messages.partner_religion_none'),
            __('messages.partner_religion_any'),
        ];


        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['partner_religion'] = $text;

        $this->saveAnswer($chatId, $state, 'partner_religion', $text, Preference::class);

        return [
            'text' => __('messages.thanks_partner_religion', ['partner_religion' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_religion');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.partner_religion_hindu')], ['text' => __('messages.partner_religion_muslim')]],
                    [['text' => __('messages.partner_religion_christian')], ['text' => __('messages.partner_religion_sikh')]],
                    [['text' => __('messages.partner_religion_buddhist')], ['text' => __('messages.partner_religion_jain')]],
                    [['text' => __('messages.partner_religion_parsi')], ['text' => __('messages.partner_religion_jewish')]],
                    [['text' => __('messages.partner_religion_tribal')], ['text' => __('messages.partner_religion_none')]],
                    [['text' => __('messages.partner_religion_any')]],
                ],

                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
