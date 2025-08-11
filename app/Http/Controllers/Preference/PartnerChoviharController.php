<?php

namespace App\Http\Controllers\Preference;

use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;
use App\Models\Preference;

class PartnerChoviharController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = ["Yes", "No", "Doesn't Matter"];
      
        if (!in_array($text, $validOptions)) {
            return [
                'text' => "Invalid option. Please select Yes or No.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'partner_chovihar', $text, \App\Models\Preference::class);

        return [
            'text' => "Thank you. Your Chovihar preference has been saved.",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        //  return __('messages.ask_partner_chovihar');
        return "Do you observe Parnter *Chovihar*?";
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'Yes'], ['text' => 'No']]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
