<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

class MobileController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        if (!preg_match('/^[6-9]\d{9}$/', $text)) {
            return [
                'text' => "❌ Invalid number. Please enter a *valid 10-digit* mobile number.:",
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                        'input_field_placeholder' => 'e.g., 9876543210'
                    ])
                ]
            ];
        }

        $answers = $state->answers;
        $answers['phone'] = $text;

        $this->saveAnswer($chatId, $state, 'phone', $text, Profile::class);
        
        // return [
        //     'text' => "✅ Mobile Number saved as *{$text}*.",
        //     'options' => [
        //         'parse_mode' => 'Markdown'
        //     ]
        // ];
        return [
            'text' => __('messages.thanks_mobile', ['mobile' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    // public static function getQuestion(): string
    // {
    //     return "📱 *Please enter your 10-digit Mobile Number* starting with 6–9:";
    // }
    // public static function getOptions(): array
    // {
    //     return [
    //         'parse_mode' => 'Markdown',
    //         'reply_markup' => json_encode([
    //             'force_reply' => true,
    //             'input_field_placeholder' => 'e.g., 9876543210'
    //         ])
    //     ];
    // }
    public static function getQuestion(): string
    {
        return __('messages.ask_mobile');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => 'e.g., 9876543210'
            ])
        ];
    }
}