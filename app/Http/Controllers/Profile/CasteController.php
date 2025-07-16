<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class CasteController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['caste'] = $text;

//         $this->saveAnswer($chatId, $state, 'caste', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_caste', ['caste' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }


//     public static function getQuestion(): string
//     {
//         return __('messages.ask_caste');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [
//                         ["text" => __('messages.caste_brahmin')],
//                         ["text" => __('messages.caste_kshatriya')],
//                     ],
//                     [
//                         ["text" => __('messages.caste_vaishya')],
//                         ["text" => __('messages.caste_shudra')],
//                     ],
//                     [["text" => __('messages.other')]],
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class CasteController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validCastes = $this->getCasteList();

        // ❌ Reject any typed input that's not in the allowed list
        if (!in_array($text, $validCastes)) {
            return [
                'text' => "❌ Please select caste from the given options below. Typing is not allowed.",
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // ✅ Save valid selection
        $answers = $state->answers ?? [];
        $answers['caste'] = $text;
        $state->answers = $answers;
        $state->save();

        $this->saveAnswer($chatId, $state, 'caste', $text, Profile::class);

        return [
            'text' => __('messages.thanks_caste', ['caste' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_caste');
    }

    public static function getOptions(): array
    {
        $keyboard = array_chunk(
            array_map(fn($caste) => ['text' => $caste], (new self())->getCasteList()),
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
        return [
            __('messages.caste_brahmin'),
            __('messages.caste_kshatriya'),
            __('messages.caste_vaishya'),
            __('messages.caste_shudra'),
            __('messages.caste_sc'),
            __('messages.caste_st'),
            __('messages.caste_obc'),
            __('messages.caste_jain'),
            __('messages.caste_sindhi'),
            __('messages.caste_rajput'),
            __('messages.caste_yadav'),
            __('messages.caste_kayastha'),
            __('messages.caste_maratha'),
            __('messages.caste_agarwal'),
            __('messages.caste_koli'),
            __('messages.caste_kumhar'),
            __('messages.caste_patel'),
            __('messages.caste_reddy'),
            __('messages.caste_kapoor'),
            __('messages.caste_gupta'),
            __('messages.caste_bania'),
            __('messages.caste_kurmi'),
            __('messages.caste_maurya'),
            __('messages.caste_chaudhary'),
            __('messages.caste_jat'),
            __('messages.caste_lodha'),
            __('messages.caste_saini'),
            __('messages.caste_teli'),
            __('messages.caste_nair'),
            __('messages.caste_menon'),
            __('messages.caste_pillai'),
            __('messages.caste_chettiar'),
            __('messages.caste_mudaliar'),
            __('messages.caste_gounder'),
            __('messages.caste_nadar'),
            __('messages.caste_ezhava'),
            __('messages.caste_naidu'),
            __('messages.caste_nayak'),
            __('messages.caste_gujar'),
            __('messages.caste_ahir'),
            __('messages.caste_meena'),
            __('messages.caste_meitei'),
            __('messages.caste_chamar'),
            __('messages.caste_dhangar'),
            __('messages.caste_giri'),
            __('messages.caste_prajapati'),
            __('messages.caste_mali'),
            __('messages.caste_bhoi'),
            __('messages.caste_bhandari'),
            __('messages.caste_sonar'),
            __('messages.caste_dhobi'),
            __('messages.caste_khatik'),
            __('messages.caste_nai'),
            __('messages.caste_kahar'),
            __('messages.caste_tonk_kshatriya'),
            __('messages.caste_bairwa'),
            __('messages.caste_paswan'),
            __('messages.caste_pal'),
            __('messages.caste_rawat'),
            __('messages.caste_thakur'),
            __('messages.caste_lingayat'),
            __('messages.caste_devanga'),
            __('messages.caste_kamma'),
            __('messages.caste_vokkaliga'),
            __('messages.caste_balija'),
            __('messages.caste_kapu'),
            __('messages.caste_jatav'),
            __('messages.caste_mochi'),
            __('messages.caste_valmiki'),
            __('messages.caste_bhatt'),
            __('messages.caste_bhils'),
            __('messages.caste_gond'),
            __('messages.caste_halba'),
            __('messages.caste_kunbi'),
            __('messages.caste_maheshwari'),
            __('messages.caste_modi'),
            __('messages.caste_oswal'),
            __('messages.caste_chandravanshi'),
            __('messages.caste_rajgond'),
            __('messages.caste_malviya'),
            __('messages.caste_dixit'),
            __('messages.caste_trivedi'),
            __('messages.caste_chaturvedi'),
            __('messages.caste_tripathi'),
            __('messages.caste_mishra'),
            __('messages.caste_sharma'),
            __('messages.caste_pandey'),
            __('messages.caste_tiwari'),
            __('messages.caste_joshi'),
            __('messages.caste_pathak'),
            __('messages.caste_dwivedi'),
            __('messages.caste_upadhyay'),
            __('messages.caste_bhargava'),
            __('messages.caste_dubey'),
            __('messages.caste_bajpai'),
            __('messages.caste_bhatnagar'),
            __('messages.caste_nigam'),
            __('messages.caste_srivastava'),
            __('messages.caste_verma'),
            __('messages.caste_other'),
        ];
    }
    
}
