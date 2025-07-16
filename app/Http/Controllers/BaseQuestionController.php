<?php

namespace App\Http\Controllers;

use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Log;

class BaseQuestionController extends Controller
{
    /**
     * Generic method to save an answer to Profile or Preference and update state
     */
    public function saveAnswer($chatId, TelegramUserState $state,string $field, string $value, string $modelClass): void {
        // Save to Profile or Preference
        $modelClass::updateOrCreate(
            ['telegram_user_id' => $chatId],
            [$field => $value]
        );

        // Save to answers array
        $answers = $state->answers ?? [];
        $answers[$field] = $value;
        // Update state
        $state->update([
            'answers' => $answers
        ]);
    }
}
