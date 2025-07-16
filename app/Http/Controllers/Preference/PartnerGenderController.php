<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

class PartnerGenderController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        // ✅ Save the partner gender
        $answers = $state->answers;
        $answers['partner_gender'] = $text;

        $this->saveAnswer($chatId, $state, 'partner_gender', $text, Preference::class);

        return [
            'text' => __('messages.partner_gender_saved', ['value' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.partner_gender_question');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'Male'], ['text' => 'Female']],
                    [['text' => 'Other']]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
