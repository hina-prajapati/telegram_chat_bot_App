<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class PartnerJobStatusController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.job_employed'),
            __('messages.job_self_employed'),
            __('messages.job_student'),
            __('messages.job_unemployed'),
            __('messages.job_service'),
            __('messages.job_business'),
            __('messages.job_home_business'),
            __('messages.job_house_maker')
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'partner_job_status', $text, Preference::class);

        return [
            'text' => __('messages.thanks_partner_job_status', ['status' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_job_status');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.job_employed')], ['text' => __('messages.job_self_employed')]],
                    [['text' => __('messages.job_student')], ['text' => __('messages.job_unemployed')]],
                    [['text' => __('messages.job_service')], ['text' => __('messages.job_business')]],
                    [['text' => __('messages.job_home_business')], ['text' => __('messages.job_house_maker')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}


