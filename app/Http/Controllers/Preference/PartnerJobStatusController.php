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
            __('messages.job_house_maker'),
            __('messages.job_unemployed'),
            __('messages.job_service'),
            __('messages.job_student'),
            __('messages.job_retired'),
            __('messages.job_prefer_not'),
            __('messages.job_any'),
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
                    [['text' => __('messages.job_house_maker')], ['text' => __('messages.job_unemployed')]],
                    [['text' => __('messages.job_student')], ['text' => __('messages.job_retired')]],
                    [['text' => __('messages.job_prefer_not')], ['text' => __('messages.job_service')]],
                    [['text' => __('messages.job_any')]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
