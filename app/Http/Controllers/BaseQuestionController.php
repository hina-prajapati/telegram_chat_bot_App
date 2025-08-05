<?php

namespace App\Http\Controllers;

use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Log;

class BaseQuestionController extends Controller
{

    public function saveAnswer($chatId, TelegramUserState $state, string $field, string $value, string $modelClass): void
    {
        // Find the profile using the telegram_user_id
        $profile = \App\Models\Profile::where('telegram_user_id', $chatId)->first();

        // Prepare the data to update
        $data = [$field => $value];

        // If saving to Preference model, also add profile_id
        if ($modelClass === \App\Models\Preference::class && $profile) {
            $data['profile_id'] = $profile->id;
        }

        // Save to Profile or Preference
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
