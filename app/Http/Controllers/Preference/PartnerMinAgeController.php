<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

class PartnerMinAgeController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        if (!ctype_digit($text) || (int)$text < 18 || (int)$text > 100) {
            return [
                'text' => "❌ Please enter a valid *minimum age* (18–100) using digits only.",
                'halt_flow' => true,
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                        'input_field_placeholder' => 'e.g., 25'
                    ])
                ]
            ];
        }

        $answers = $state->answers;
        $answers['partner_min_age'] = (int) $text;

        $this->saveAnswer($chatId, $state, 'partner_min_age', $text, Preference::class);

        return [
            'text' => "✅ Preferred *minimum age* saved as *{$text}*.",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_partner_min_age');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => __('messages.example_age') // e.g., 25
            ])
        ];
    }
}
