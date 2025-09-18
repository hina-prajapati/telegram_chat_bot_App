<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class BioController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        if (mb_strlen($text) > 255) {
            return [
                'text' => __('messages.bio_too_long', ['max' => 255]) . "\n\n" . __('messages.ask_bio'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['bio'] = $text;

        $this->saveAnswer($chatId, $state, 'bio', $text, Profile::class);

        return [
            'text' => __('messages.thanks_bio', ['bio' => $text]),
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_bio');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true,
                'input_field_placeholder' => 'e.g., I love reading and traveling...'
            ])
        ];
    }
}

