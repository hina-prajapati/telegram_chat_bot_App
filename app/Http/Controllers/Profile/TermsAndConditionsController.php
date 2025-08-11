<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseQuestionController;

// class TermsAndConditionsController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $text = strtolower(trim($text));

//         if (!in_array($text, ['accept', 'reject'])) {
//             return [
//                 'text' => "❌ Please type *accept* or *reject* to proceed.",
//                 'options' => self::getOptions(),
//                 'halt_flow' => true
//             ];
//         }

//         $enumValue = $text === 'accept' ? 'accepted' : 'rejected';
//         $answers = $state->answers ?? [];

//         $answers['terms_and_conditions'] = $text;

//         $this->saveAnswer($chatId, $state, 'terms_and_conditions', $enumValue, Profile::class);

//         $state->answers = $answers;
//         $state->current_step = null;
//         $state->save();

//         $telegramController = app(\App\Http\Controllers\Profile\TelegramController::class);
//         return $telegramController->showProfile($chatId, $answers);

//         return [
//             'text' => $text === 'accept'
//                 ? "✅ Thank you for accepting the terms and conditions."
//                 : "❌ You have rejected the terms. You may not be able to use all features.",
//             'options' => []
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return "📄 Please read and *accept* or *reject* the terms and conditions.";
//     }

//     public static function getOptions(array $answers = []): array
//     {
//         return [
//             'reply_markup' => json_encode([
//                 'keyboard' => [['accept'], ['reject']],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class TermsAndConditionsController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        // Normalize user input: remove spaces + lowercase
        $text = strtolower(trim($text)); // becomes "accept" or "reject"

        // Debug log (optional)
        Log::info("🚨 Normalized T&C input: ", ['text' => $text]);

        if (!in_array($text, ['accept', 'reject'])) {
            return [
                'text' => "❌ Please type *accept* or *reject* to proceed.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // You can store the accepted/rejected state in proper case or enum
        $enumValue = $text === 'accept' ? 'accepted' : 'rejected';

        if ($enumValue === 'rejected') {
            // Delete user data
            \App\Models\Profile::where('telegram_user_id', $chatId)->delete();
            \App\Models\Preference::where('telegram_user_id', $chatId)->delete();

            $state->update([
                'answers' => [],
                'current_step' => null,
            ]);

            return [
                'text' => "❌ You have rejected the terms. Your data has been deleted.",
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode(['remove_keyboard' => true])
                ],
                'halt_flow' => true
            ];
        }

        // Save accepted terms
        $this->saveAnswer($chatId, $state, 'terms_and_conditions', $enumValue, Profile::class);

        $state->update([
            'answers' => array_merge($state->answers ?? [], ['terms_and_conditions' => $enumValue]),
            'current_step' => null
        ]);

        // Show profile
        $telegramController = app(\App\Http\Controllers\Profile\TelegramController::class);
        return $telegramController->showProfile($chatId, $state->answers ?? []);
    }


    public static function getQuestion(): string
    {
        return "📄 Please read and *accept* or *reject* the terms and conditions.";
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [['Accept'], ['Reject']],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]),
            'parse_mode' => 'Markdown'
        ];
    }
}
