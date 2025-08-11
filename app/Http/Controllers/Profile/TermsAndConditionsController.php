<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class TermsAndConditionsController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = strtolower(trim($text));

        if (!in_array($text, ['accept', 'reject'])) {
            return [
                'text' => "❌ Please type *accept* or *reject* to proceed.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $enumValue = $text === 'accept' ? 'accepted' : 'rejected';

        $this->saveAnswer($chatId, $state, 'terms_and_conditions', $enumValue, Profile::class);

        return [
            'text' => $text === 'accept'
                ? "✅ Thank you for accepting the terms and conditions."
                : "❌ You have rejected the terms. You may not be able to use all features.",
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return "📄 Please read and *accept* or *reject* the terms and conditions.";
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [['accept'], ['reject']],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}


