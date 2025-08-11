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
    protected $reasons = [
        'I found a match from this portal',
        'I found a match from other portal',
        "I'm taking a break",
        'Not getting suitable matches',
        'Created account by mistake',
        'Other'
    ];

    public function handleDeletion($chatId, $text, TelegramUserState $state)
    {
        if (strtolower($text) === '/delete_profile') {
            $profile = Profile::where('telegram_user_id', $chatId)->first();

            if (!$profile) {
                return $this->sendMessage($chatId, "❌ You don't have a profile to delete.");
            }

            $state->current_step = 'deletion_reason';
            $state->save();

            // Send the predefined options as a keyboard
            return $this->sendMessage(
                $chatId,
                "🗑️ Please select the reason why you want to delete your profile:",
                $this->getReasonKeyboard()
            );
        }

        if ($state->current_step === 'deletion_reason') {
            // Check if the input text matches one of the predefined reasons
            if (!in_array($text, $this->reasons, true)) {
                return $this->sendMessage(
                    $chatId,
                    "❌ Invalid option selected. Please choose a reason from the keyboard below.",
                    $this->getReasonKeyboard()
                );
            }

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

    private function getReasonKeyboard()
    {
        $keyboard = [];

        // Put each reason in its own row
        foreach ($this->reasons as $reason) {
            $keyboard[] = [['text' => $reason]];
        }

        return [
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
