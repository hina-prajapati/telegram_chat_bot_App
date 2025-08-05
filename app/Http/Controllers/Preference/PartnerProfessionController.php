<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseQuestionController;

class PartnerProfessionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);
        $allowedProfessions = self::allowedProfessions();

        if (!in_array($text, $allowedProfessions, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['profession'] = $text;

        $this->saveAnswer($chatId, $state, 'profession', $text, Preference::class);

        return [
            'text' => __('messages.thanks_profession', ['profession' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_profession');
    }

    public static function getOptions(): array
    {
        $professions = DB::table('profession_categories')->pluck('name')->toArray();

        // Format into rows of 2 buttons each
        $keyboard = [];
        for ($i = 0; $i < count($professions); $i += 2) {
            $row = [];
            $row[] = ['text' => $professions[$i]];
            if (isset($professions[$i + 1])) {
                $row[] = ['text' => $professions[$i + 1]];
            }
            $keyboard[] = $row;
        }

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private static function allowedProfessions(): array
    {
        return DB::table('profession_categories')->pluck('name')->toArray();
    }
}
