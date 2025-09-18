<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseQuestionController;

class StateController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $states = DB::table('states')->pluck('name')->toArray();
        $states[] = 'Other';

        if (!in_array($text, $states)) {
            return [
                'text' => "❌ Invalid state. Please select a valid option from the keyboard.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['state'] = $text;

        $this->saveAnswer($chatId, $state, 'state', $text, Profile::class);

        return [
            'text' => __('messages.thanks_state', ['state' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_state');
    }

    public static function getOptions(): array
    {
        $states = DB::table('states')->pluck('name')->toArray();

        $keyboard = array_map(
            function ($chunk) {
                return array_map(fn($state) => ['text' => $state], $chunk);
            },
            array_chunk($states, 2)
        );

        $keyboard[] = [['text' => 'Other']];

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ];
    }
}
