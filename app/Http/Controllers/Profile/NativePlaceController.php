<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class NativePlaceController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        if (mb_strlen($text) > 100) {
            return [
                'text' => "❌ Native place is too long (max 100 characters). Please try again.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'native_place', $text, Profile::class);

        return [
            'text' => "🏡 Native place saved: *{$text}*",
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return "🏡 What is your native place?";
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => 'e.g., Jaipur, Rajasthan'
            ])
        ];
    }
}

