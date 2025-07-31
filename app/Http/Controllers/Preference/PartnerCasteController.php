<?php

namespace App\Http\Controllers\Preference;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Preference;

class PartnerCasteController extends BaseQuestionController
{
    // public function handle($chatId, $text, TelegramUserState $state)
    // {
    //     $allowedValues = $this->getAllowedCastes();
    //     $matchedKey = array_search($text, $allowedValues, true);

    //     if ($matchedKey === false) {
    //         return [
    //             'text' => __('messages.invalid_caste_selection'),
    //             'options' => self::getOptions()
    //         ];
    //     }

    //     // $answers = $state->answers;
    //     // $answers['partner_caste'] = ucfirst($matchedKey);

    //     // $this->saveAnswer($chatId, $state, 'partner_caste', $matchedKey, Preference::class);

    //     $answers = $state->answers;

    //     // Capitalize first letter
    //     $matchedKey = ucfirst(strtolower($matchedKey));

    //     $answers['partner_caste'] = $matchedKey;

    //     $this->saveAnswer($chatId, $state, 'partner_caste', $matchedKey, Preference::class);

    //     return [
    //         'text' => __('messages.partner_caste_saved', ['value' => $allowedValues[$matchedKey]]),
    //         'options' => [
    //             'parse_mode' => 'Markdown'
    //         ]
    //     ];
    // }

    public function handle($chatId, $text, TelegramUserState $state)
    {
        $allowedValues = $this->getAllowedCastes();
        $matchedKey = array_search($text, $allowedValues, true);

        if ($matchedKey === false) {
            return [
                'text' => __('messages.invalid_caste_selection'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $answers = $state->answers;

        // Capitalize only the value to store, not the key
        $answers['partner_caste'] = ucfirst($matchedKey);

        $this->saveAnswer($chatId, $state, 'partner_caste', ucfirst($matchedKey), Preference::class);

        return [
            'text' => __('messages.partner_caste_saved', ['value' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_partner_caste');
    }

    public static function getOptions(array $answers = []): array
    {
        $castes = (new self())->getAllowedCastes();
        $keyboard = array_chunk(
            array_map(fn($value) => ['text' => $value], array_values($castes)),
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

    private function getAllowedCastes(): array
    {
        return [
            'brahmin'         => __('messages.caste_brahmin'),
            'kshatriya'       => __('messages.caste_kshatriya'),
            'vaishya'         => __('messages.caste_vaishya'),
            'shudra'          => __('messages.caste_shudra'),
            'sc'              => __('messages.caste_sc'),
            'st'              => __('messages.caste_st'),
            'obc'             => __('messages.caste_obc'),
            'jain'            => __('messages.caste_jain'),
            'sindhi'          => __('messages.caste_sindhi'),
            'rajput'          => __('messages.caste_rajput'),
            'yadav'           => __('messages.caste_yadav'),
            'kayastha'        => __('messages.caste_kayastha'),
            'maratha'         => __('messages.caste_maratha'),
            'agarwal'         => __('messages.caste_agarwal'),
            'koli'            => __('messages.caste_koli'),
            'kumhar'          => __('messages.caste_kumhar'),
            'patel'           => __('messages.caste_patel'),
            'reddy'           => __('messages.caste_reddy'),
            'kapoor'          => __('messages.caste_kapoor'),
            'gupta'           => __('messages.caste_gupta'),
            'bania'           => __('messages.caste_bania'),
            'kurmi'           => __('messages.caste_kurmi'),
            'maurya'          => __('messages.caste_maurya'),
            'chaudhary'       => __('messages.caste_chaudhary'),
            'jat'             => __('messages.caste_jat'),
            'lodha'           => __('messages.caste_lodha'),
            'saini'           => __('messages.caste_saini'),
            'teli'            => __('messages.caste_teli'),
            'nair'            => __('messages.caste_nair'),
            'menon'           => __('messages.caste_menon'),
            'pillai'          => __('messages.caste_pillai'),
            'chettiar'        => __('messages.caste_chettiar'),
            'mudaliar'        => __('messages.caste_mudaliar'),
            'gounder'         => __('messages.caste_gounder'),
            'nadar'           => __('messages.caste_nadar'),
            'ezhava'          => __('messages.caste_ezhava'),
            'naidu'           => __('messages.caste_naidu'),
            'nayak'           => __('messages.caste_nayak'),
            'gujar'           => __('messages.caste_gujar'),
            'ahir'            => __('messages.caste_ahir'),
            'meena'           => __('messages.caste_meena'),
            'meitei'          => __('messages.caste_meitei'),
            'chamar'          => __('messages.caste_chamar'),
            'dhangar'         => __('messages.caste_dhangar'),
            'giri'            => __('messages.caste_giri'),
            'prajapati'       => __('messages.caste_prajapati'),
            'mali'            => __('messages.caste_mali'),
            'bhoi'            => __('messages.caste_bhoi'),
            'bhandari'        => __('messages.caste_bhandari'),
            'sonar'           => __('messages.caste_sonar'),
            'dhobi'           => __('messages.caste_dhobi'),
            'khatik'          => __('messages.caste_khatik'),
            'nai'             => __('messages.caste_nai'),
            'kahar'           => __('messages.caste_kahar'),
            'tonk_kshatriya'  => __('messages.caste_tonk_kshatriya'),
            'bairwa'          => __('messages.caste_bairwa'),
            'paswan'          => __('messages.caste_paswan'),
            'pal'             => __('messages.caste_pal'),
            'rawat'           => __('messages.caste_rawat'),
            'thakur'          => __('messages.caste_thakur'),
            'lingayat'        => __('messages.caste_lingayat'),
            'devanga'         => __('messages.caste_devanga'),
            'kamma'           => __('messages.caste_kamma'),
            'vokkaliga'       => __('messages.caste_vokkaliga'),
            'kapu'            => __('messages.caste_kapu'),
            'jatav'           => __('messages.caste_jatav'),
            'mochi'           => __('messages.caste_mochi'),
            'valmiki'         => __('messages.caste_valmiki'),
            'bhatt'           => __('messages.caste_bhatt'),
            'bhils'           => __('messages.caste_bhils'),
            'gond'            => __('messages.caste_gond'),
            'halba'           => __('messages.caste_halba'),
            'kunbi'           => __('messages.caste_kunbi'),
            'maheshwari'      => __('messages.caste_maheshwari'),
            'modi'            => __('messages.caste_modi'),
            'oswal'           => __('messages.caste_oswal'),
            'chandravanshi'   => __('messages.caste_chandravanshi'),
            'rajgond'         => __('messages.caste_rajgond'),
            'malviya'         => __('messages.caste_malviya'),
            'dixit'           => __('messages.caste_dixit'),
            'trivedi'         => __('messages.caste_trivedi'),
            'chaturvedi'      => __('messages.caste_chaturvedi'),
            'tripathi'        => __('messages.caste_tripathi'),
            'mishra'          => __('messages.caste_mishra'),
            'sharma'          => __('messages.caste_sharma'),
            'pandey'          => __('messages.caste_pandey'),
            'tiwari'          => __('messages.caste_tiwari'),
            'joshi'           => __('messages.caste_joshi'),
            'pathak'          => __('messages.caste_pathak'),
            'dwivedi'         => __('messages.caste_dwivedi'),
            'upadhyay'        => __('messages.caste_upadhyay'),
            'bhargava'        => __('messages.caste_bhargava'),
            'dubey'           => __('messages.caste_dubey'),
            'bajpai'          => __('messages.caste_bajpai'),
            'bhatnagar'       => __('messages.caste_bhatnagar'),
            'nigam'           => __('messages.caste_nigam'),
            'srivastava'      => __('messages.caste_srivastava'),
            'verma'           => __('messages.caste_verma'),
            'any'           => __('messages.caste_any'),
        ];
    }
}
