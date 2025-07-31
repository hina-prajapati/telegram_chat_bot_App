<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Models\Profile;

class EmailController extends BaseQuestionController
{
    // public function handle($chatId, $text, TelegramUserState $state)
    // {
    //     $answers = $state->answers;
    //     $answers['email'] = $text;

    //     $this->saveAnswer($chatId, $state, 'email', $text, Profile::class);

    //     return [
    //         'text' => __('messages.thanks_email', ['email' => $text]),
    //         'options' => []
    //     ];

    // }
    public function handle($chatId, $text, TelegramUserState $state)
    {
        // Validate the email format
        if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
            return [
                'text' => "❌ Invalid email format. Please enter a valid email address (e.g., example@email.com).",
                'options' => self::getOptions(),
                  'halt_flow' => true
                // ask again with force_reply
            ];
        }

        $answers = $state->answers;
        $answers['email'] = $text;

        $this->saveAnswer($chatId, $state, 'email', $text, Profile::class);

        return [
            'text' => __('messages.thanks_email', ['email' => $text]),
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_email');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true
            ])
        ];
    }
}
