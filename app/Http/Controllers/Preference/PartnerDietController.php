<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;

class PartnerDietController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
    // Log::info('User state updated', $state->toArray());


        $validOptions = ['Veg', 'Non-Veg', 'Jain', 'Any'];
        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.partner_invalid_diet'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'partner_diet', $text, Preference::class);

        if($text === 'jain'){
            $state->current_step = 'chovihar';
             $state->save();
        

        return [
                'text' => "Do you observe *Chovihar*?",
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [['text' => 'Yes'], ['text' => 'No']]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ],
                'halt_flow' => true
            ];
        }

        return [
            'text' => __('messages.saved_diet', ['diet' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_diet');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'Veg'], ['text' => 'Non-Veg']],
                    [['text' => 'Jain'], ['text' => 'Any']]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}