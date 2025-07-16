<?php

namespace App\Http\Controllers\Profile;

use App\Notifications\RequestReceivedNotification;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\{
    TelegramUserState,
    TelegramMessage,
    Profile,
    Preference,
    Gallery,
    DailyMatch,
    MatchRequest
};
use Illuminate\Support\Facades\{Log, Http};
use App\Http\Controllers\Profile\{
    NameController,
    BioController,
    EmailController,
    MaritalStatusController,
    DobController,
    StateController,
    CityController,
    MotherTongueController,
    ReligionController,
    CasteController,
    EducationLevelController,
    EducationFieldController,
    WorkingSectorController,
    ProfessionController,
    MobileController,
    ProfilePhotoController,
    DietController,
    SmokingController,
    DrinkingController,
    HeightController,
    BodyTypeController,
    SkinToneController,
    GenderController,
    JobStatusController,
    IncomeRangeController
};
use App\Http\Controllers\Preference\{
    PartnerMaritalStatusController,
    PartnerCasteController,
    PartnerIncomeRangeController,
    PartnerMinAgeController,
    PartnerMaxAgeController,
    PartnerMinHeightController,
    PartnerMaxHeightController,
    // PartnerGenderController,
    PartnerLanguageController
};

class TelegramController extends Controller
{

    public function handleUpdate(Request $request)
    {
        Log::info('📥 Incoming Telegram Update', ['update' => $request->all()]);
        $update = $request->all();

        // ✅ Handle callback buttons
        if (isset($update['callback_query'])) {
            return $this->handleCallback($update['callback_query']);
        }

        $message = $update['message'] ?? null;
        if (!$message) {
            Log::warning('⚠️ No message found in update.');
            return response('ok');
        }

        $chatId = $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        Log::info('👤 Message received', ['chat_id' => $chatId, 'text' => $text]);
        $state = TelegramUserState::where('telegram_user_id', $chatId)->first();

        if (!$state) {
            $state = TelegramUserState::create([
                'telegram_user_id' => $chatId,
                'current_step' => 'selecting_language',
                'answers' => [],
                'language' => 'en'
            ]);
        }

        // App::setLocale($state->language ?? 'en');
        App::setLocale($state->language ?? config('app.locale'));


        $handlers = $this->getHandlers();
        $currentStep = $state->current_step;
        $answers = $state->answers ?? [];

        if (strtolower($text) === '/start') {
            return $this->handleStartCommand($chatId, $state);
        }

        if ($currentStep === 'selecting_language') {
            return $this->handleLanguageSelection($chatId, $text, $state);
        }

        if (isset($handlers[$currentStep])) {
            $controller = app($handlers[$currentStep]);

            if ($currentStep === 'awaiting_profile_photo') {
                return $this->handlePhotoUpload($chatId, $message, $text, $state, $controller, $handlers);
            }

            if ($state->current_step === 'awaiting_dob') {
                $dobController = new DobController();
                $response = $dobController->handle($chatId, $text, $state);

                // Only move to next step if dob was fully captured
                if (!isset($state->answers['dob_day'])) {
                    return $this->sendStructuredMessage($chatId, $response); // ↩️ Stay in DOB flow
                }
            }

            $response = $controller->handle($chatId, $text, $state);
            // $this->sendStructuredMessage($chatId, $response);
            if (isset($response['halt_flow']) && $response['halt_flow'] === true) {
                return $this->sendStructuredMessage($chatId, $response);
            }

            $nextStep = $this->getNextStep($currentStep);
            if ($nextStep && isset($handlers[$nextStep])) {
                $state->update(['current_step' => $nextStep]);
                $nextController = app($handlers[$nextStep]);

                return $this->sendMessage(
                    $chatId,
                    $nextController::getQuestion(),
                    method_exists($nextController, 'getOptions') ? $nextController::getOptions($state->answers ?? []) : []
                );
            } else {
                $state->update(['current_step' => null]);
                $matchController = app(\App\Http\Controllers\MatchController::class);
                return $matchController->findMatches($chatId, $state->answers ?? []);
                // return $this->findMatches($chatId, $state->answers ?? []);
            }
        }

        Log::info('🟡 No handler found for current step.');
        return $this->sendMessage($chatId, "Type /start to begin.");
    }

    private function getHandlers(): array
    {
        return [
            'awaiting_name' => NameController::class,
            'awaiting_income_range' => IncomeRangeController::class,
            'awaiting_partner_income_range' => PartnerIncomeRangeController::class,

            'awaiting_bio' => BioController::class,
            'awaiting_email' => EmailController::class,
            'awaiting_gender' => GenderController::class,
            'awaiting_marital_status' => MaritalStatusController::class,
            'awaiting_dob' => DobController::class,
            'awaiting_state' => StateController::class,
            'awaiting_city' => CityController::class,
            'awaiting_mother_tongue' => MotherTongueController::class,
            'awaiting_religion' => ReligionController::class,
            'awaiting_caste' => CasteController::class,
            'awaiting_education_level' => EducationLevelController::class,
            'awaiting_education_field' => EducationFieldController::class,
            'awaiting_job_status' => JobStatusController::class,
            'awaiting_working_sector' => WorkingSectorController::class,
            'awaiting_profession' => ProfessionController::class,
            'awaiting_mobile' => MobileController::class,
            'awaiting_profile_photo' => ProfilePhotoController::class,
            'awaiting_diet' => DietController::class,
            'awaiting_smoking' => SmokingController::class,
            'awaiting_drinking' => DrinkingController::class,
            'awaiting_height' => HeightController::class,
            'awaiting_body_type' => BodyTypeController::class,
            'awaiting_skin_tone' => SkinToneController::class,
            'awaiting_partner_marital_status' => PartnerMaritalStatusController::class,
            'awaiting_partner_caste' => PartnerCasteController::class,
            'awaiting_partner_min_age' => PartnerMinAgeController::class,
            'awaiting_partner_max_age' => PartnerMaxAgeController::class,
            // 'awaiting_partner_gender' => PartnerGenderController::class,
            'awaiting_partner_min_height' => PartnerMinHeightController::class,
            'awaiting_partner_max_height' => PartnerMaxHeightController::class,
            'awaiting_partner_language' => PartnerLanguageController::class,
        ];
    }

    private function hasCompletedProfile($chatId)
    {
        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $preference = Preference::where('telegram_user_id', $chatId)->first();

        if (!$profile || !$preference) {
            Log::info('❌ Missing profile or preference data');
            return false;
        }

        $requiredProfileFields = [
            'name',
            'email',
            'marital_status',
            'dob',
            'state',
            'city',
            'mother_tongue',
            'religion',
            'caste',
            'education_level',
            'education_field',
            'job_status',
            'working_sector',
            'profession',
            'phone',
            'diet',
            'smoking',
            'drinking',
            'height',
            'body_type',
            'skin_tone',
            'gender'
            // ✅ Removed 'profile_photo' to make it optional
        ];

        $requiredPreferenceFields = [
            'partner_marital_status',
            'partner_caste',
            'partner_min_age',
            'partner_max_age',
            'partner_min_height',
            'partner_max_height',
            // 'partner_gender',
            'partner_language'
        ];

        foreach ($requiredProfileFields as $field) {
            if (empty($profile->$field)) {
                Log::info("❌ Missing profile field: $field");
                return false;
            }
        }

        foreach ($requiredPreferenceFields as $field) {
            if (empty($preference->$field)) {
                Log::info("❌ Missing preference field: $field");
                return false;
            }
        }

        return true;
    }

    private function sendStructuredMessage($chatId, $data)
    {
        $text = $data['text'] ?? '';
        $options = $data['options'] ?? [];

        Log::info('📤 Sending structured message', ['chat_id' => $chatId, 'text' => $text]);
        return $this->sendMessage($chatId, $text, $options);
    }

    public function sendMessage($chatId, $text, $options = [])
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (empty($token)) {
            Log::error('❌ TELEGRAM_BOT_TOKEN is not set in .env file.');
            return;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $payload = array_merge([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ], $options);

        $response = Http::post($url, $payload);

        Log::info('📩 Telegram sendMessage response', ['body' => $response->body()]);
        if (!$response->ok()) {
            Log::error('❌ Telegram sendMessage failed', ['body' => $response->body()]);
        }

        return $response->json();
    }

    public function sendPhoto($chatId, $photoUrlOrPath, $caption = '')
    {
        // If it's a local path, use multipart
        if (file_exists(public_path('uploads/profiles/' . basename($photoUrlOrPath)))) {
            $response = Http::attach(
                'photo',
                file_get_contents(public_path('uploads/profiles/' . basename($photoUrlOrPath))),
                basename($photoUrlOrPath)
            )->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto", [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
            ]);
        } else {
            // Otherwise, fallback to URL
            $response = Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto", [
                'chat_id' => $chatId,
                'photo' => $photoUrlOrPath,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
            ]);
        }

        Log::info('sendPhoto response: ' . $response->body());
    }

    public function showProfile($chatId, $answers = [])
    {
        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $preference = Preference::where('telegram_user_id', $chatId)->first();

        $gallery = Gallery::where('profile_id', $profile->id)
            ->latest('created_at')
            ->first();

        if (!$profile || !$preference) {
            return $this->sendMessage($chatId, "❌ Profile or preferences not found.");
        }

        // ✅ Build summary
        $summary = "*👤 Your Profile:*\n";

        $orderedFields = [
            'name',
            'email',
            'bio',
            'gender',
            'marital_status',
            'dob',
            'state',
            'city',
            'mother_tongue',
            'religion',
            'caste',
            'height',
            'education_level',
            'education_field',
            'job_status',
            'working_sector',
            'profession',
            'phone',
            'profile_photo',
            'diet',
            'smoking',
            'drinking',
            'height',
            'body_type',
            'skin_tone',
        ];

        foreach ($orderedFields as $field) {
            if (!empty($profile->$field)) {
                $label = ucwords(str_replace('_', ' ', $field));

                // 🎯 Format dob here
                if ($field === 'dob') {
                    $formattedDob = \Carbon\Carbon::parse($profile->dob)->format('d-m-Y');
                    $summary .= "▪️ *$label*: $formattedDob\n";
                } else {
                    $summary .= "▪️ *$label*: {$profile->$field}\n";
                }
            }
        }

        $summary .= "\n*💘 Your Preferences:*\n";
        foreach ($preference->getAttributes() as $key => $value) {
            if (
                !in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at']) &&
                !empty($value) // ✅ skip empty/null/false/'' values
            ) {
                $label = ucwords(str_replace('_', ' ', $key));
                $summary .= "🔸 *$label*: $value\n";
            }
        }

        $filename = ($gallery && $gallery->image_path)
            ? $gallery->image_path
            : 'profile_Pic.jpg'; // ✅ fallback default

        $photoPath = public_path('uploads/profiles/' . $filename);

        // ✅ Always send photo — either user's or default
        if (file_exists($photoPath)) {
            $this->sendPhoto($chatId, $photoPath, $summary, ['parse_mode' => 'Markdown']);
        } else {
            $photoUrl = secure_asset('uploads/profiles/' . $filename);
            $this->sendPhoto($chatId, $photoUrl, $summary, ['parse_mode' => 'Markdown']);
        }
        // ✅ Now show match button
        $matchController = app(\App\Http\Controllers\MatchController::class);
        return $matchController->findMatches($chatId, $profile);
        // return $this->handleNextMatch($chatId, $profile);
    }

    private function getNextStep(string $currentStep): ?string
    {
        $steps = array_keys($this->getHandlers());
        $index = array_search($currentStep, $steps);
        return $steps[$index + 1] ?? null;
    }

    protected function handleStartCommand($chatId, TelegramUserState $state)
    {
        if ($this->hasCompletedProfile($chatId) && !$state->current_step) {
            Log::info("✅ Showing profile for already completed user: $chatId");
            $state->update(['current_step' => null, 'answers' => []]);
            return $this->showProfile($chatId);
        }

        // If not completed, show language selection
        $state->update([
            'current_step' => 'selecting_language',
            'answers' => [],
            'language' => null,
        ]);

        return $this->sendMessage($chatId, __('messages.language_select'), [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => 'English'], ['text' => 'हिन्दी']],
                    [['text' => 'मराठी'], ['text' => 'ગુજરાતી']],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }

    protected function handleLanguageSelection($chatId, $text, TelegramUserState $state)
    {
        $languages = [
            'English' => 'en',
            'हिन्दी' => 'hi',
            'मराठी' => 'mr',
            'ગુજરાતી' => 'gu',
        ];

        if (array_key_exists($text, $languages)) {
            $lang = $languages[$text];
            App::setLocale($lang);

            $state->update([
                'language' => $lang,
                'current_step' => 'awaiting_name',
                'answers' => [],
            ]);

            return $this->sendMessage(
                $chatId,
                __('messages.registration_welcome') . "\n\n" . NameController::getQuestion(),
                NameController::getOptions()
            );
        }

        return $this->sendMessage($chatId, __('messages.language_invalid'));
    }

    protected function handlePhotoUpload($chatId, $message, $text, $state, $controller, $handlers)
    {
        // Multilingual skip/done commands
        $skipCommands = [
            'en' => ['skip photo', 'done'],
            'hi' => ['फोटो छोड़ें', 'पूरा हुआ'],
            'mr' => ['फोटो वगळा', 'झाले'],
            'gu' => ['ફોટો છોડો', 'પૂર્ણ થયું'],
        ];

        $lang = $state->language ?? 'en';
        $allowedSkips = $skipCommands[$lang] ?? $skipCommands['en'];

        if (in_array(mb_strtolower(trim($text)), array_map('mb_strtolower', $allowedSkips))) {
            $nextStep = $this->getNextStep('awaiting_profile_photo');
            $state->update(['current_step' => $nextStep]);

            $nextController = app($handlers[$nextStep]);

            return $this->sendMessage(
                $chatId,
                $nextController::getQuestion(),
                method_exists($nextController, 'getOptions') ? $nextController::getOptions($state->answers ?? []) : []
            );
        }

        if (isset($message['photo'])) {
            Log::info('📸 Handling profile photo upload');
            $response = $controller->handle($chatId, $message['photo'], $state);
            return $this->sendStructuredMessage($chatId, $response);
        }

        return $this->sendMessage(
            $chatId,
            __('messages.profile_photo_invalid_text') // Fallback localized text
        );
    }

    // protected function handleCallback(array $callback)
    // {
    //     $callbackData = $callback['data'];
    //     $chatId = $callback['message']['chat']['id'];

    //     Log::info('🔘 Callback received', ['chat_id' => $chatId, 'data' => $callbackData]);

    //     $profile = Profile::where('telegram_user_id', $chatId)->first();

    //     // ✅ Next Match
    //     if ($callbackData === 'next_match') {
    //         $matchController = app(\App\Http\Controllers\MatchController::class);
    //         return $matchController->findMatches($chatId, $profile);
    //     }

    //     // ✅ Send Request
    //     if (str_starts_with($callbackData, 'send_request_')) {
    //         $matchId = str_replace('send_request_', '', $callbackData);
    //         return $this->handleSendRequest($chatId, $matchId);
    //     }

    //     // ✅ Show all previous matches
    //     if ($callbackData === 'previous_match') {
    //         $previousMatches = \App\Models\DailyMatch::where('telegram_user_id', $chatId)
    //             ->with('matchedProfile')
    //             ->latest('shown_at')
    //             ->get();

    //         if ($previousMatches->isEmpty()) {
    //             return $this->sendMessage($chatId, "❌ No previous profiles available.");
    //         }

    //         foreach ($previousMatches as $matchRecord) {
    //             $match = $matchRecord->matchedProfile;

    //             if (!$match) {
    //                 continue;
    //             }

    //             $summary = "*👤 Previous Match:*\n";
    //             $summary .= "▪️ *Name:* {$match->name}\n";
    //             $summary .= "▪️ *Gender:* {$match->gender}\n";
    //             $summary .= "▪️ *Caste:* {$match->caste}\n";
    //             $summary .= "▪️ *Height:* {$match->height} ft\n";
    //             $summary .= "▪️ *City:* {$match->city}\n";
    //             $summary .= "▪️ *Phone:* {$match->phone}\n";

    //             // ✅ Send photo if exists
    //             $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //             $photoPath = public_path('uploads/profiles/' . $image);

    //             if (file_exists($photoPath)) {
    //                 $photoUrl = asset('uploads/profiles/' . $image);
    //                 $this->sendPhoto($chatId, $photoUrl);
    //             }

    //             // ✅ Send profile summary with action buttons
    //             $this->sendMessage($chatId, $summary, [
    //                 'parse_mode' => 'Markdown',
    //                 'reply_markup' => json_encode([
    //                     'inline_keyboard' => [
    //                         [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]],
    //                         [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']]
    //                     ]
    //                 ])
    //             ]);
    //         }

    //         return response('ok');
    //     }

    //     return response('ok');
    // }

    // protected function handleCallback(array $callback)
    // {
    //     $callbackData = $callback['data'];
    //     $chatId = $callback['message']['chat']['id'];

    //     Log::info('🔘 Callback received', ['chat_id' => $chatId, 'data' => $callbackData]);

    //     if (str_starts_with($callbackData, 'send_request_')) {
    //         $matchId = str_replace('send_request_', '', $callbackData);
    //         return $this->handleSendRequest($chatId, $matchId);
    //     }

    //     if ($callbackData === 'next_match') {
    //         $profile = Profile::where('telegram_user_id', $chatId)->first();
    //         $matchController = app(\App\Http\Controllers\MatchController::class);
    //         return $matchController->findMatches($chatId, $profile);
    //     }

    //     return $this->sendMessage($chatId, "❌ Unknown action.");
    // }

    // public function handleSendRequest($chatId, $matchId)
    // {
    //     $matchUser = Profile::find($matchId);
    //     $currentUser = Profile::where('telegram_user_id', $chatId)->first();

    //     if (!$matchUser || !$currentUser) {
    //         return $this->sendMessage($chatId, "❌ Unable to send request.");
    //     }
    //     return $this->sendMessage($chatId, "✅ Request sent to *{$matchUser->name}*!", [
    //         'parse_mode' => 'Markdown'
    //     ]);
    // }


    public function handleSendRequest($chatId, $matchId)
    {
        $matchUser = Profile::find($matchId);
        $currentUser = Profile::where('telegram_user_id', $chatId)->first();

        if (!$matchUser || !$currentUser) {
            return $this->sendMessage($chatId, "❌ Unable to send request.");
        }

        // 💾 Check if already requested
        $alreadyRequested = MatchRequest::where('sender_id', $chatId)
            ->where('receiver_id', $matchUser->id)
            ->exists();

        if ($alreadyRequested) {
            return $this->sendMessage($chatId, "⚠️ You've already sent a request to {$matchUser->name}.");
        }

        // Save to DB
        MatchRequest::create([
            'sender_id' => $chatId,
            'receiver_id' => $matchUser->id,
            'status' => 'pending',
        ]);

        // Send Telegram notification
        $matchUser->notify(new RequestReceivedNotification($currentUser));

        return $this->sendMessage($chatId, "✅ Request sent to *{$matchUser->name}*!", [
            'parse_mode' => 'Markdown'
        ]);
    }
}


























// Match Controller


<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\DailyMatch;
use App\Models\Preference;
use App\Models\MatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Profile\TelegramController;

class MatchController extends TelegramController
{

    //  without using daily matches getting data on next matched

    // public function findMatches($chatId)
    // {
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
    //     }

    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
    //     }

    //     $userGender = strtolower($userProfile->gender);
    //     $oppositeGender = $userGender === 'male' ? 'female' : 'male';

    //     $seenKey = "seen_matches_user_{$chatId}";
    //     $seenIds = cache()->get($seenKey, []);
    //     $lastKey = "last_match_user_{$chatId}";

    //     try {
    //         $query = $this->buildQuery($preference, $chatId, $oppositeGender)
    //             ->whereNotIn('id', $seenIds);
    //     } catch (\Exception $e) {
    //         return $this->sendMessage($chatId, "❌ Error building match query.");
    //     }

    //     $match = $query->first();

    //     if (!$match) {
    //         cache()->forget($seenKey); // Clear seen cache to allow new round
    //         $lastMatchId = cache()->get($lastKey);

    //         if ($lastMatchId) {
    //             return $this->sendMessage($chatId, "😔 No more matches found.", [
    //                 'reply_markup' => json_encode([
    //                     'inline_keyboard' => [
    //                         [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']]
    //                     ]
    //                 ])
    //             ]);
    //         }

    //         return $this->sendMessage($chatId, "😔 No more matches found.");
    //     }

    //     // ✅ Save this match as seen
    //     $seenIds[] = $match->id;
    //     cache()->put($seenKey, $seenIds, now()->addHours(12));
    //     // cache()->put($lastKey, $match->id, now()->addHours(6));

    //     // 🧠 Also store in history (for previous)
    //     // $historyKey = "match_history_user_{$chatId}";
    //     // $history = cache()->get($historyKey, []);
    //     // $history[] = $match->id;
    //     // cache()->put($historyKey, $history, now()->addHours(12));

    //     // Summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} ft\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     // Image
    //     try {
    //         $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //         $path = public_path('uploads/profiles/' . $image);

    //         if (file_exists($path)) {
    //             $this->sendPhoto($chatId, $path);
    //         }

    //         $this->sendMessage($chatId, $summary, [
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => [
    //                     [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]],
    //                     [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']],
    //                 ]
    //             ])
    //         ]);
    //     } catch (\Exception $e) {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }
    // }




    // looping many times next match
    // public function findMatches($chatId)
    // {
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
    //     }

    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
    //     }

    //     $userGender = strtolower($userProfile->gender);
    //     $oppositeGender = $userGender === 'male' ? 'female' : 'male';

    //     $seenKey = "seen_matches_user_{$chatId}";
    //     $lastKey = "last_match_user_{$chatId}";
    //     $historyKey = "match_history_user_{$chatId}";

    //     $seenIds = cache()->get($seenKey, []);
    //     $history = cache()->get($historyKey, []);

    //     try {
    //         $query = $this->buildQuery($preference, $chatId, $oppositeGender)
    //             ->whereNotIn('id', $seenIds);
    //     } catch (\Exception $e) {
    //         return $this->sendMessage($chatId, "❌ Error building match query.");
    //     }

    //     $match = $query->first();

    //     if (!$match) {
    //         cache()->forget($seenKey);

    //         $lastMatchId = cache()->get($lastKey);
    //         if ($lastMatchId) {
    //             return $this->sendMessage($chatId, "😔 No more matches found.", [
    //                 'reply_markup' => json_encode([
    //                     'inline_keyboard' => [
    //                         [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']]
    //                     ]
    //                 ])
    //             ]);
    //         }

    //         return $this->sendMessage($chatId, "😔 No more matches found.");
    //     }

    //     // ✅ Update seen & history
    //     $seenIds[] = $match->id;
    //     $seenIds = array_unique($seenIds);
    //     cache()->put($seenKey, $seenIds, now()->addHours(12));

    //     $history[] = $match->id;
    //     cache()->put($historyKey, $history, now()->addHours(12));
    //     cache()->put($lastKey, $match->id, now()->addHours(6));

    //     // ✅ Prepare summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} ft\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     try {
    //         $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //         $path = public_path('uploads/profiles/' . $image);

    //         if (file_exists($path)) {
    //             $this->sendPhoto($chatId, $path);
    //         }

    //         $buttons = [
    //             [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]],
    //             [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']]
    //         ];

    //         // ✅ If there's a previous match, add previous button
    //         // if (count($history) > 1) {
    //         //     $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
    //         // }

    //         $this->sendMessage($chatId, $summary, [
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ])
    //         ]);
    //     } catch (\Exception $e) {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }
    // }

    // public function findMatches($chatId)
    // {
    //     // ✅ Step 1: Fetch user preference
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
    //     }

    //     // ✅ Step 2: Get user profile
    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
    //     }

    //     $userGender = strtolower($userProfile->gender);
    //     $oppositeGender = $userGender === 'male' ? 'female' : 'male';

    //     // ✅ Step 3: Get already matched user IDs from DailyMatch
    //     $alreadyMatchedIds = DailyMatch::where('telegram_user_id', $chatId)
    //         ->pluck('matched_user_id')
    //         ->toArray();

    //     // ✅ Step 4: Build query and exclude already matched profiles
    //     try {
    //         $query = $this->buildQuery($preference, $chatId, $oppositeGender)
    //             ->whereNotIn('id', $alreadyMatchedIds);
    //     } catch (\Exception $e) {
    //         return $this->sendMessage($chatId, "❌ Error building match query.");
    //     }

    //     $match = $query->first();

    //     if (!$match) {
    //         return $this->sendMessage($chatId, "😔 No new matches found.");
    //     }

    //     // ✅ Step 5: Save in DailyMatch to prevent re-showing
    //     DailyMatch::create([
    //         'telegram_user_id' => $chatId,
    //         'matched_user_id' => $match->id,
    //         'shown_at' => now(),
    //     ]);

    //     // ✅ Step 6: Prepare summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} ft\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     // ✅ Step 7: Send photo + summary
    //     try {
    //         $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //         $path = public_path('uploads/profiles/' . $image);

    //         if (file_exists($path)) {
    //             $this->sendPhoto($chatId, $path);
    //         }

    //         $this->sendMessage($chatId, $summary, [
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => [
    //                     [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]],
    //                     [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']]
    //                 ]
    //             ])
    //         ]);
    //     } catch (\Exception $e) {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }
    // }



    // next match previous match
    public function findMatches($chatId)
    {
        // Step 1: Get preference
        $preference = Preference::where('telegram_user_id', $chatId)->first();
        if (!$preference) {
            return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
        }

        // Step 2: Get user profile
        $userProfile = Profile::where('telegram_user_id', $chatId)->first();
        if (!$userProfile || empty($userProfile->gender)) {
            return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
        }

        $userGender = strtolower($userProfile->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        // Step 3: Get already matched IDs
        $shownIds = DailyMatch::where('telegram_user_id', $chatId)->pluck('matched_user_id')->toArray();

        // Step 4: Build query
        try {
            $query = $this->buildQuery($preference, $chatId, $oppositeGender)
                ->whereNotIn('id', $shownIds);
        } catch (\Exception $e) {
            return $this->sendMessage($chatId, "❌ Error building match query.");
        }

        // Step 5: Get next match
        $match = $query->first();

        if (!$match) {
            // If no more new match, check if any shown matches exist for Previous
            $lastMatch = DailyMatch::where('telegram_user_id', $chatId)
                ->latest('shown_at')
                ->with('matchedProfile') // assuming you have relation set up
                ->first();

            $buttons = [];

            if ($lastMatch && $lastMatch->matchedProfile) {
                $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
            }

            return $this->sendMessage($chatId, "😔 No more matches found.", [
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ])
            ]);
        }

        // Step 6: Save this match to DailyMatch
        DailyMatch::create([
            'telegram_user_id' => $chatId,
            'matched_user_id' => $match->id,
            'shown_at' => now(),
        ]);

        // Step 7: Build match summary
        $summary = "*👤 Match Found:*\n";
        $summary .= "▪️ *Name:* {$match->name}\n";
        $summary .= "▪️ *Gender:* {$match->gender}\n";
        $summary .= "▪️ *Caste:* {$match->caste}\n";
        $summary .= "▪️ *Height:* {$match->height} ft\n";
        $summary .= "▪️ *City:* {$match->city}\n";
        // $summary .= "▪️ *Phone:* {$match->phone}\n";

        // Step 8: Send image and message
        try {
            $image = $match->profile_photo ?? 'profile_Pic.jpg';
            $path = public_path('uploads/profiles/' . $image);

            if (file_exists($path)) {
                $this->sendPhoto($chatId, $path);
            }
           $senderProfile = Profile::where('telegram_user_id', $chatId)->first();

            $existingRequest = \App\Models\MatchRequest::where('sender_id', $senderProfile->id)
                ->where('receiver_id', $match->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();
                
            $buttons = [];

            if ($existingRequest) {
                if ($existingRequest->status === 'approved') {
                    $buttons[] = [['text' => '✅ Request Approved', 'callback_data' => 'noop']];
                } elseif ($existingRequest->status === 'pending') {
                    $buttons[] = [['text' => '⏳ Request Pending', 'callback_data' => 'noop']];
                }
            } else {
                $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
            }

            $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];


            $this->sendMessage($chatId, $summary, [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ])
            ]);
            // cache()->forget("previous_index_user_{$chatId}");

        } catch (\Exception $e) {
            $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
        }
    }




    // Check If User Already Got a Match Today

    // Load User Preferences

    // Load User Profile

    // Determine Opposite Gender

    // Build Query Based on Preferences

    // Exclude Already Shown Matches

    // Get First Matching Record

    // Save Match to daily_matches Table

    // Prepare and Send Match Summary

    // Send Profile Photo or Just Text

    // Send Optional “Next Match” Button


    //cron with limited record with next match
    // public function findMatches($chatId)
    // {
    //     $today = \Carbon\Carbon::today();

    //     // ⛔ Already shown today?
    //     $alreadyShownToday = DailyMatch::where('telegram_user_id', $chatId)
    //         ->whereDate('shown_at', $today)
    //         ->first();

    //     if ($alreadyShownToday) {
    //         return $this->sendMessage($chatId, "✅ You've already seen your match for today. Come back tomorrow!");
    //     }

    //     // 🔍 Load preferences
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found.");
    //     }

    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Profile or gender is missing.");
    //     }

    //     $oppositeGender = strtolower($userProfile->gender) === 'male' ? 'female' : 'male';

    //     // 🧠 Build query
    //     $query = $this->buildQuery($preference, $chatId, $oppositeGender);

    //     // 🧼 Exclude already shown matches (all time)
    //     $alreadyShownIds = DailyMatch::where('telegram_user_id', $chatId)
    //         ->pluck('matched_user_id')
    //         ->toArray();
    //     $query->whereNotIn('id', $alreadyShownIds);

    //     // 🔍 Get one match
    //     $match = $query->first();
    //     if (!$match) {
    //         return $this->sendMessage($chatId, "😔 No new matches found.");
    //     }

    //     // 📝 Save today's match
    //     DailyMatch::create([
    //         'telegram_user_id' => $chatId,
    //         'matched_user_id' => $match->id,
    //         'shown_at' => now(),
    //     ]);

    //     // 🧾 Match Summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} cm\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     // 🖼️ Photo
    //     $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //     $path = public_path('uploads/profiles/' . $image);

    //     if (file_exists($path)) {
    //         $this->sendPhoto($chatId, $path, $summary, ['parse_mode' => 'Markdown']);
    //     } else {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }

    //     // ✅ Show next button (optional, even if disabled for today)
    //     return $this->sendMessage($chatId, "⏭️ Want more matches?", [
    //         'reply_markup' => json_encode([
    //             'inline_keyboard' => [
    //                 [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']]
    //             ]
    //         ]),
    //         'parse_mode' => 'Markdown'
    //     ]);
    // }

    private function buildQuery($preference, $excludeUserId, $matchGender)
    {
        $query = Profile::query()
            ->where('telegram_user_id', '!=', $excludeUserId)
            ->whereRaw('LOWER(gender) = ?', [$matchGender]); // Cross-gender match

        $profile = Profile::where('telegram_user_id', $excludeUserId)->first();

        // $age = \Carbon\Carbon::parse($profile->dob)->age;
        // Log::info("User age:", ['age' => $age]);

        if ($preference->partner_min_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
        }


        if ($preference->partner_max_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
        }

        // if ($preference->partner_min_height) {
        //     $query->where('height', '>=', $preference->partner_min_height);
        // }

        // if ($preference->partner_max_height) {
        //     $query->where('height', '<=', $preference->partner_max_height);
        // }

        if ($preference->partner_marital_status) {
            $query->where('marital_status', $preference->partner_marital_status);
        }

        if ($preference->partner_caste) {
            $query->where('caste', $preference->partner_caste);
        }

        if ($preference->partner_language) {
            $query->where('mother_tongue', $preference->partner_language);
        }

        // Profile Table

        // if ($profile && $profile->education_level) {
        //     $query->where('education_level', $profile->education_level);
        // }

        // if ($profile && $profile->education_field) {
        //     $query->where('education_field', $profile->education_field);
        // }

        // if ($profile && $profile->job_status) {
        //     $query->where('job_status', $profile->job_status);
        // }

        // if ($profile && $profile->mother_tongue) {
        //     $query->where('mother_tongue', $profile->mother_tongue);
        // }

        return $query->with('galleries');
    }
}
