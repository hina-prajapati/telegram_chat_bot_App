<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\TelegramController;
use App\Models\Profile;
use App\Models\Preference;
use App\Models\TelegramUserState;
use App\Models\DeletionReason;
use Illuminate\Http\Request;

class ReasonController extends TelegramController
{
    public function handleDeletion($chatId, $text, TelegramUserState $state)
    {
        if (strtolower($text) === '/delete_profile') {
            $profile = Profile::where('telegram_user_id', $chatId)->first();

            if (!$profile) {
                return $this->sendMessage($chatId, "❌ You don't have a profile to delete.");
            }

            $state->current_step = 'deletion_reason';
            $state->save();

            return $this->sendMessage($chatId, "🗑️ Please tell us the reason why you want to delete your profile:");
        }

        if ($state->current_step === 'deletion_reason') {
            // Save deletion reason
            DeletionReason::create([
                'telegram_user_id' => $chatId,
                'reason' => $text,
            ]);

            // Delete user data
            Profile::where('telegram_user_id', $chatId)->delete();
            Preference::where('telegram_user_id', $chatId)->delete();

            // Clear the state
            $state->delete();

            return $this->sendMessage($chatId, "✅ Your profile has been deleted. Thank you for your feedback.");
        }

        return null;
    }
}
