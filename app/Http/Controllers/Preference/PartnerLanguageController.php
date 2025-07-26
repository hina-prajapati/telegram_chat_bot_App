<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use App\Models\TelegramUserState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\BaseQuestionController;

// class PartnerLanguageController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers ?? [];
//         $answers['partner_language'] = $text;

//         $this->saveAnswer($chatId, $state, 'partner_language', $text, Preference::class);

//         $state->answers = $answers;
//         $state->current_step = null;
//         $state->save();
//         $telegramController = app(\App\Http\Controllers\Profile\TelegramController::class);
//         return $telegramController->showProfile($chatId, $answers);
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.partner_language_question');
//     }

//     public static function getOptions(array $answers = []): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => 'Hindi'], ['text' => 'English']],
//                     [['text' => 'Gujarati'], ['text' => 'Marathi']],
//                     [['text' => 'Punjabi'], ['text' => 'Other']]
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class PartnerLanguageController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $allowedLanguages = self::allowedOptions();
        $text = trim($text);

        if (!in_array($text, $allowedLanguages, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers ?? [];
        $answers['partner_language'] = $text;

        $this->saveAnswer($chatId, $state, 'partner_language', $text, Preference::class);

        $state->answers = $answers;
        $state->current_step = null;
        $state->save();

        $telegramController = app(\App\Http\Controllers\Profile\TelegramController::class);
        return $telegramController->showProfile($chatId, $answers);
    }

    public static function getQuestion(): string
    {
        return __('messages.partner_language_question');
    }

    public static function getOptions(array $answers = []): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'Hindi'], ['text' => 'English']],
                    [['text' => 'Gujarati'], ['text' => 'Marathi']],
                    [['text' => 'Punjabi'], ['text' => 'Any']]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedOptions(): array
    {
        return ['Hindi', 'English', 'Gujarati', 'Marathi', 'Punjabi', 'Any'];
    }
}

