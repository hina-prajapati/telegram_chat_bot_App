<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

class PartnerMaxAgeController extends BaseQuestionController
{
    // public function handle($chatId, $text, TelegramUserState $state)
    // {
    //     // Accept only digits between 18 and 100
    //     if (!ctype_digit($text) || (int)$text < 18 || (int)$text > 100) {
    //         return [
    //             'text' => "❌ Please enter a valid *max age* (18–100) using digits only.",
    //             'halt_flow' => true,
    //             'options' => [
    //                 'parse_mode' => 'Markdown',
    //                 'reply_markup' => json_encode([
    //                     'force_reply' => true,
    //                     'input_field_placeholder' => 'e.g., 85'
    //                 ])
    //             ]
    //         ];
    //     }

    //     // Save valid answer
    //     $answers = $state->answers;
    //     $answers['partner_max_age'] = (int) $text;

    //     $this->saveAnswer($chatId, $state, 'partner_max_age', $text, Preference::class);

    //     return [
    //         'text' => "✅ Preferred *max age age* saved as *{$text}*.",
    //         'options' => ['parse_mode' => 'Markdown']
    //     ];
    // }

    public function handle($chatId, $text, TelegramUserState $state)
    {
        // Basic digit and range check
        if (!ctype_digit($text) || (int)$text < 18 || (int)$text > 100) {
            return [
                'text' => "❌ Please enter a valid *maximum age* (18–100) using digits only.",
                'halt_flow' => true,
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                        'input_field_placeholder' => 'e.g., 85'
                    ])
                ]
            ];
        }

        $maxAge = (int) $text;
        $answers = $state->answers;

        // Check if min age is already set and validate the range
        if (isset($answers['partner_min_age']) && $maxAge < $answers['partner_min_age']) {
            return [
                'text' => "⚠️ *Maximum age* must be greater than or equal to the *minimum age* ({$answers['partner_min_age']}). Please enter a valid number.",
                'halt_flow' => true,
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                        'input_field_placeholder' => 'e.g., 85'
                    ])
                ]
            ];
        }

        // Save valid max age
        $answers['partner_max_age'] = $maxAge;
        $this->saveAnswer($chatId, $state, 'partner_max_age', $maxAge, Preference::class);

        return [
            'text' => "✅ Preferred *maximum age* saved as *{$maxAge}*.",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }



    public static function getQuestion(): string
    {
        return __('messages.partner_max_age_question');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => __('messages.example_age') // e.g., 30
            ])
        ];
    }
}
