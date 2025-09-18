<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseQuestionController;

class PartnerSubCasteController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers ?? [];

        // ⛔ Ensure caste is selected before sub-caste
        if (empty($answers['partner_caste'])) {
            return [
                'text' => "❗ Please select your Parnter Caste first before choosing a Partner Sub-Caste.",
                'halt_flow' => true
            ];
        }

        $validSubCastes = $this->getSubCasteList($answers['partner_caste']);

        // ❌ Reject manually typed inputs
        if (!in_array($text, $validSubCastes)) {
            return [
                'text' => "❌ Please select a Sub-Caste from the list below.",
                'options' => self::getOptions($answers),
                'halt_flow' => true
            ];
        }

        // ✅ Save selection
        $answers['partner_sub_caste'] = $text;
        $state->answers = $answers;
        $state->save();

        $this->saveAnswer($chatId, $state, 'partner_sub_caste', $text, Preference::class);

        return [
            'text' => "✅ Sub-Caste saved as *{$text}*.",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_sub_caste'); // Add this key in your lang file
    }

    public static function getOptions(array $answers = []): array
    {
        if (empty($answers['partner_caste'])) {
            return [
                'text' => '❗ Please select a Partner Caste first.',
                'options' => [],
                'halt_flow' => true
            ];
        }

        $subCastes = (new self())->getSubCasteList($answers['partner_caste']);
        $keyboard = array_chunk(
            array_map(fn($item) => ['text' => $item], $subCastes),
            2
        );

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    private function getSubCasteList(string $casteName): array
    {
        $caste = DB::table('casts')->where('caste_name', $casteName)->first();

        if (!$caste) return [];

        return DB::table('subcasts')
            ->where('caste_id', $caste->caste_id)
            ->pluck('sub_caste_name')
            ->toArray();
    }
}
