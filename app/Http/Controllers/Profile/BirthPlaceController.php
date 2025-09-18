<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class BirthPlaceController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        if (mb_strlen($text) > 100) {
            return [
                'text' => "❌ Birth place is too long (max 100 characters). Please try again.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'birth_place', $text, Profile::class);

        return [
            'text' => "📍 Birth place saved: *{$text}*",
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return "📍 Where were you born?";
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => 'e.g., Delhi, India'
            ])
        ];
    }
}

