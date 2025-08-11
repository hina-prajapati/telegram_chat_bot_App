<?php

namespace App\Http\Controllers\Preference;

use App\Models\Preference;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseQuestionController;

class PartnerCasteController extends BaseQuestionController
{

     public function handle($chatId, $text, TelegramUserState $state)
    {
        $validCastes = $this->getCasteList();

        // ❌ Block manual inputs not in list
        if (!in_array($text, $validCastes)) {
            return [
                'text' => "❌ Please select a caste from the given options below. Typing is not allowed.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // ✅ Save selection
        $answers = $state->answers ?? [];
        $answers['partner_caste'] = $text;
        $state->answers = $answers;
        $state->save();

        $this->saveAnswer($chatId, $state, 'partner_caste', $text, Preference::class);

        return [
            'text' => __('messages.thanks_partner_caste', ['partner_caste' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_partner_caste');
    }

    public static function getOptions(): array
    {
        $castes = (new self())->getCasteList();

        $keyboard = array_chunk(
            array_map(fn($caste) => ['text' => $caste], $castes),
            2
        );

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'is_persistent' => true
            ])
        ];
    }

   private function getCasteList(): array
    {
        $castes = DB::table('casts')
            ->orderBy('caste_name')
            ->pluck('caste_name')
            ->toArray();

        $castes = array_filter($castes, function ($caste) {
            return strtolower($caste) !== 'other';
        });

        $castes = array_values($castes);

        array_push($castes, 'Any');

        return $castes;
    }

}
