<?php

namespace App\Http\Controllers;

use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Log;

class BaseQuestionController extends Controller
{

    public function saveAnswer($chatId, TelegramUserState $state, string $field, string $value, string $modelClass): void
    {
        $profile = \App\Models\Profile::where('telegram_user_id', $chatId)->first();

        $data = [$field => $value];
        // Log::info("Saved answer for chatId: {$chatId}, field: {$field}, value: {$value}");
        if ($modelClass === \App\Models\Preference::class && $profile) {
            $data['profile_id'] = $profile->id;
        }

        $modelClass::updateOrCreate(
            ['telegram_user_id' => $chatId],
            $data
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
