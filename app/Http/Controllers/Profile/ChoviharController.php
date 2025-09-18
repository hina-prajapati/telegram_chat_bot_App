<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class ChoviharController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = ['Yes', 'No', "Doesn't Matter"];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => "Invalid option. Please select Yes, No, or Doesn't Matter.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'chovihar', $text, \App\Models\Profile::class);

        return [
            'text' => "✅ Your Chovihar preference has been saved.",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return "Do you observe *Chovihar*?";
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'Yes'], ['text' => 'No']],
                    [["text" => "Doesn't Matter"]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}


