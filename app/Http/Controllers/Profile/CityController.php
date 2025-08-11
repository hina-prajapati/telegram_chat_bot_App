<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class CityController extends BaseQuestionController
{

    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers ?? [];

        // Check if state is selected
        if (empty($answers['state'])) {
            return [
                'text' => "⚠️ Please select a state first.",
                'options' => self::getOptions($answers),
                'halt_flow' => true
            ];
        }

        // Fetch valid cities for selected state
        $selectedState = $answers['state'];
        $selectedStateRecord = DB::table('states')->where('name', $selectedState)->first();

        if (!$selectedStateRecord) {
            return [
                'text' => "❌ Invalid state selection. Please go back and select a valid state.",
                'options' => self::getOptions($answers),
                'halt_flow' => true
            ];
        }

        $cities = DB::table('cities')
            ->where('state_id', $selectedStateRecord->id)
            ->pluck('name')
            ->toArray();

        $cities[] = 'Other';

        // ✅ Validate user input
        if (!in_array($text, $cities)) {
            return [
                'text' => "❌ Invalid city. Please select a valid option from the keyboard.",
                'options' => self::getOptions($answers),
                'halt_flow' => true
            ];
        }

        // Save valid city
        $answers['city'] = $text;
        $this->saveAnswer($chatId, $state, 'city', $text, Profile::class);

        return [
            'text' => __('messages.thanks_city', ['city' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_city');
    }

    public static function getOptions(array $answers = []): array
    {
        $selectedState = $answers['state'] ?? null;

        if (!$selectedState) {
            return [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => [[['text' => __('messages.select_state_first')]]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ];
        }

        $state = DB::table('states')->where('name', $selectedState)->first();

        if (!$state) {
            return [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => [[['text' => 'Other']]], // optionally translate 'Other'
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ];
        }

        $cities = DB::table('cities')
            ->where('state_id', $state->id)
            ->pluck('name')
            ->toArray();

        if (empty($cities)) {
            $cities = ['Other'];
        }

        $keyboard = array_map(
            fn($chunk) => array_map(fn($city) => ['text' => $city], $chunk),
            array_chunk($cities, 2)
        );

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
