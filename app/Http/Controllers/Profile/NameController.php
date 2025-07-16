<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Models\Profile;

class NameController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers;
        $answers['name'] = $text;

        $this->saveAnswer($chatId, $state, 'name', $text, Profile::class);

        return [
            'text' => __('messages.thanks_name', ['name' => $text]),
            'options' => []
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_name');
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
