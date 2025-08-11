<?php

namespace App\Http\Controllers\Profile;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseQuestionController;
use App\Models\Profile;

class SpecificProfessionController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $text = trim($text);

        $allowedProfessions = self::allowedProfessions();

        if (!in_array($text, $allowedProfessions, true)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions($state),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;
        $answers['specific_profession'] = $text;

        $this->saveAnswer($chatId, $state, 'specific_profession', $text, Profile::class);

        return [
            'text' => __('messages.thanks_specific_profession', ['specific_profession' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_specific_profession');
    }

    public static function getOptions($state = null): array
    {
        $selectedCategory = is_array($state)
            ? ($state['profession'] ?? null)
            : ($state->answers['profession'] ?? null);

        if (!$selectedCategory) {
            return [
                'text' => '❌ No category selected. Please select a profession category first.',
                'options' => [
                    'keyboard' => [['Back']],
                    'resize_keyboard' => true
                ]
            ];
        }

        // Get category_id by category name
        $category = DB::table('profession_categories')->where('name', $selectedCategory)->first();

        if (!$category) {
            return [
                'text' => '❌ Invalid profession category.',
                'options' => [
                    'keyboard' => [['Back']],
                    'resize_keyboard' => true
                ]
            ];
        }

        // Fetch specific professions for the selected category
        $specifics = DB::table('specific_professions')
            ->where('category_id', $category->id)
            ->pluck('name')
            ->toArray();

        // Format into keyboard
        $keyboard = [];
        for ($i = 0; $i < count($specifics); $i += 2) {
            $row = [['text' => $specifics[$i]]];
            if (isset($specifics[$i + 1])) {
                $row[] = ['text' => $specifics[$i + 1]];
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
        return DB::table('specific_professions')->pluck('name')->toArray();
    }
}
