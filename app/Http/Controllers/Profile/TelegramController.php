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
use Illuminate\Support\Facades\DB;
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
    IncomeRangeController,
    SubCasteController,
    SpecificProfessionController,
    ChoviharController,
    BirthTimeController,
    BirthPlaceController,
    NativePlaceController,
    TermsAndConditionsController,
    ReasonController
};
use App\Http\Controllers\Preference\{
    PartnerMaritalStatusController,
    PartnerCasteController,
    PartnerChoviharController,
    PartnerDietController,
    PartnerEducationLevelController,
    PartnerIncomeRangeController,
    PartnerJobStatusController,
    PartnerMinAgeController,
    PartnerMaxAgeController,
    PartnerMinHeightController,
    PartnerMaxHeightController,
    // PartnerGenderController,
    PartnerLanguageController,
    PartnerProfessionController,
    PartnerReligionController,
    PartnerSpecificProfessionController,
    // PartnerSubCasteController,

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
                // 'current_step' => 'selecting_language',
                'current_step' => 'awaiting_name',
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
            if ($this->hasCompletedProfile($chatId)) {
                return $this->sendMessage($chatId, "✅ You already have a completed profile.");
            }

            // Otherwise continue onboarding
            return $this->handleStartCommand($chatId, $state);
        }

        // In your command handler
        if ($text === '/support') {
            $state->update([
                'current_step' => 'awaiting_support_message'
            ]);

            return $this->sendMessage($chatId, "💬 Please type your query. Our support team will get back to you.");
        }

        if ($state->current_step === 'awaiting_support_message') {
            \App\Models\SupportQuery::create([
                'chat_id' => $chatId,
                'username' => $message['from']['username'] ?? null,
                'message' => $text,
            ]);

            $state->update(['current_step' => null]);

            return $this->sendMessage($chatId, "✅ Thanks! Your query has been submitted. Our team will contact you soon.");
        }

        if (strtolower($text) === '/profile') {

            $profile = Profile::where('telegram_user_id', $chatId)->first();
            $preference = Preference::where('telegram_user_id', $chatId)->first();

            if (!$profile || !$preference) {
                return $this->sendMessage($chatId, "❌ You haven't created a profile yet. Type /start to begin.");
            }

            return $this->showProfile($chatId);
        }

        if (strtolower($text) === '/matches') {
            // Check if profile exists
            $profile = Profile::where('telegram_user_id', $chatId)->first();
            if (!$profile) {
                return $this->sendMessage($chatId, "❌ You need to create a profile first. Type /start.");
            }
           $matchMessage = "🎉 *Good news!* We've found a new profile that matches your preferences. ✨\n\nYour daily dose of curated matches is ready to be explored.";
            $this->sendMessage($chatId, $matchMessage);
            $matchController = app(\App\Http\Controllers\MatchController::class);
            return $matchController->findMatches($chatId, $profile);
        }

        if (strtolower($text) === '/approved') {
            $profile = Profile::where('telegram_user_id', $chatId)->first();

            if (!$profile) {
                return $this->sendMessage($chatId, "❌ You need to create a profile first using /start.");
            }

            // Fetch all approved match requests
            $approvedRequests = MatchRequest::where('status', 'approved')
                ->where(function ($query) use ($profile) {
                    $query->where('sender_id', $profile->id)
                        ->orWhere('receiver_id', $profile->id);
                })
                ->get();

            if ($approvedRequests->isEmpty()) {
                return $this->sendMessage($chatId, "😕 You haven't approved or been approved by any users yet.");
            }

            // Loop through and show other profiles using existing method
            foreach ($approvedRequests as $request) {
                $otherProfileId = $request->sender_id == $profile->id
                    ? $request->receiver_id
                    : $request->sender_id;

                $this->showOtherProfile($chatId, $otherProfileId, true); // true = reveal contact
            }

            return response('ok');
        }

        if (strtolower($text) === '/update_profile') {
            $profile = Profile::where('telegram_user_id', $chatId)->first();
            $preference = Preference::where('telegram_user_id', $chatId)->first();

            $profile = Profile::where('telegram_user_id', $chatId)->first();
            $preference = Preference::where('telegram_user_id', $chatId)->first();

            // Step 2: Validation
            if (!$profile || !$preference) {
                return $this->sendMessage($chatId, "❌ No profile or preferences found. Type /start to create your profile.");
            }
            $profileId = $profile->id;

            // Step 3: Ensure profile_id in preferences matches profile table
            if ($preference->profile_id != $profile->id) {
                return $this->sendMessage($chatId, "❌ Mismatch in profile linkage. Please contact support.");
            }

            $editUrl = "http://127.0.0.1:8000/profile/edit/{$profileId}?chat_id={$chatId}";
            $editUrl = "http://127.0.0.1:8000/profile/edit/{$profileId}?chat_id={$chatId}";


            return $this->sendMessage($chatId, "📝 Click the link below to update your profile:\n\n<a href='$editUrl'>Edit Profile</a>", [
                'parse_mode' => 'HTML'
            ]);
        }

        if (strtolower($text) === '/pending') {
            $profile = Profile::where('telegram_user_id', $chatId)->first();

            if (!$profile) {
                return $this->sendMessage($chatId, "❌ You need to create a profile first using /start.");
            }

            // Show only requests sent *to* the user (they can approve/reject)
            $pendingRequests = MatchRequest::where('status', 'pending')
                ->where('receiver_id', $profile->id)
                ->get();

            if ($pendingRequests->isEmpty()) {
                return $this->sendMessage($chatId, "😕 You have no pending match requests.");
            }

            foreach ($pendingRequests as $request) {
                $senderProfile = Profile::find($request->sender_id);

                if (!$senderProfile) continue;

                // 👇 show profile with image and basic details using existing method
                app(TelegramController::class)->showOtherProfile($chatId, $senderProfile->id, false);

                // ✅ Now send buttons for approve/reject
                // $buttons = [
                //     [
                //         ['text' => '✅ Approve', 'callback_data' => "approve_request__{$senderProfile->id}_{$profile->id}"],
                //         ['text' => '❌ Reject', 'callback_data' => "reject_request__{$senderProfile->id}_{$profile->id}"]
                //     ]
                // ];

                $buttons = [
                    [
                        ['text' => '✅ Approve', 'callback_data' => "approve_request_{$senderProfile->id}_{$profile->id}"],
                        ['text' => '❌ Reject', 'callback_data' => "reject_request_{$senderProfile->id}_{$profile->id}"]
                    ]
                ];

                $this->sendMessage($chatId, "What would you like to do?", [
                    'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
                    'parse_mode' => 'Markdown'
                ]);
            }

            return response('ok');
        }

        if (strtolower($text) === '/delete_profile' || $state->current_step === 'deletion_reason') {
            $controller = new \App\Http\Controllers\Profile\ReasonController();
            $controller = new ReasonController();
            return $controller->handleDeletion($chatId, $text, $state);
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
                    return $this->sendStructuredMessage($chatId, $response);
                }
            }

            $response = $controller->handle($chatId, $text, $state);
            // $this->sendStructuredMessage($chatId, $response);
            if (isset($response['halt_flow']) && $response['halt_flow'] === true) {
                return $this->sendStructuredMessage($chatId, $response);
            }

            $nextStep = $this->getNextStep($currentStep, $state);
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
            'awaiting_email' => EmailController::class,
            'awaiting_bio' => BioController::class,
            'awaiting_gender' => GenderController::class,
            'awaiting_mobile' => MobileController::class,
            'awaiting_marital_status' => MaritalStatusController::class,
            'awaiting_dob' => DobController::class,
            'awaiting_birth_time' => BirthTimeController::class,
            'awaiting_birth_place' => BirthPlaceController::class,
            'awaiting_native_place' => NativePlaceController::class,
            'awaiting_state' => StateController::class,
            'awaiting_city' => CityController::class,
            'awaiting_mother_tongue' => MotherTongueController::class,
            'awaiting_religion' => ReligionController::class,
            'awaiting_caste' => CasteController::class,
            'awaiting_sub_caste' => SubCasteController::class,
            'awaiting_education_level' => EducationLevelController::class,
            'awaiting_education_field' => EducationFieldController::class,
            'awaiting_job_status' => JobStatusController::class,
            'awaiting_working_sector' => WorkingSectorController::class,
            'awaiting_profession' => ProfessionController::class,
            'awaiting_specific_profession' => SpecificProfessionController::class,
            'awaiting_income_range' => IncomeRangeController::class,
            'awaiting_profile_photo' => ProfilePhotoController::class,
            'awaiting_diet' => DietController::class,
            'awaiting_chovihar' => ChoviharController::class,
            'awaiting_smoking' => SmokingController::class,
            'awaiting_drinking' => DrinkingController::class,
            'awaiting_height' => HeightController::class,
            'awaiting_body_type' => BodyTypeController::class,
            'awaiting_skin_tone' => SkinToneController::class,
            'awaiting_partner_marital_status' => PartnerMaritalStatusController::class,
            'awaiting_partner_caste' => PartnerCasteController::class,
            // 'awaiting_partner_sub_caste' => PartnerSubCasteController::class,
            'awaiting_partner_profession' => PartnerProfessionController::class,
            'awaiting_partner_sepcific_profession' => PartnerSpecificProfessionController::class,
            'awaiting_partner_min_age' => PartnerMinAgeController::class,
            'awaiting_partner_max_age' => PartnerMaxAgeController::class,
            'awaiting_partner_religion' => PartnerReligionController::class,
            'awaiting_partner_diet' => PartnerDietController::class,
            'awaiting_partner_chovihar' => PartnerChoviharController::class,
            'awaiting_partner_education_level' => PartnerEducationLevelController::class,
            'awaiting_partner_job_status' => PartnerJobStatusController::class,
            'awaiting_partner_income_range' => PartnerIncomeRangeController::class,
            'awaiting_partner_min_height' => PartnerMinHeightController::class,
            'awaiting_partner_max_height' => PartnerMaxHeightController::class,
            'awaiting_partner_language' => PartnerLanguageController::class,
            'awaiting_terms_and_conditions' => TermsAndConditionsController::class,
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
        ], is_array($options) ? $options : []);

        $response = Http::post($url, $payload);

        Log::info('📩 Telegram sendMessage response', ['body' => $response->body()]);
        if (!$response->ok()) {
            Log::error('❌ Telegram sendMessage failed', ['body' => $response->body()]);
        }

        return $response->json();
    }

    public function sendPhoto($chatId, $photoUrlOrPath, $caption = '', $extraOptions = [])
    {
        $isLocal = file_exists(public_path('uploads/profiles/' . basename($photoUrlOrPath)));

        if ($isLocal) {
            $response = Http::attach(
                'photo',
                file_get_contents(public_path('uploads/profiles/' . basename($photoUrlOrPath))),
                basename($photoUrlOrPath)
            )->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto", array_merge([
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
            ], $extraOptions));
        } else {
            if (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $photoUrlOrPath)) {
                Log::error("Invalid image URL: $photoUrlOrPath");
                return;
            }

            $response = Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto", array_merge([
                'chat_id' => $chatId,
                'photo' => $photoUrlOrPath,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
            ], $extraOptions));
        }

        Log::info('sendPhoto response: ' . $response->body());
    }

    public function showProfile($chatId, $answers = [])
    {
        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $preference = Preference::where('telegram_user_id', $chatId)->first();

        $gallery = Gallery::where('profile_id', $profile->id)
            ->orderBy('id', 'asc')
            ->first();


        if (!$profile || !$preference) {
            return $this->sendMessage($chatId, "❌ Profile or preferences not found.");
        }
        $profileSummary = "*👤 Your Profile:*\n";

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
            'sub_caste',
            'height',
            'education_level',
            'education_field',
            'job_status',
            'working_sector',
            'profession',
            'phone',
            'diet',
            'smoking',
            'drinking',
            'body_type',
            'skin_tone',
        ];

        // foreach ($orderedFields as $field) {
        //     if (!empty($profile->$field)) {
        //         $label = ucwords(str_replace('_', ' ', $field));

        //         if ($field === 'dob') {
        //             $formattedDob = \Carbon\Carbon::parse($profile->dob)->format('d-m-Y');
        //             $profileSummary .= "▪️ *$label*: $formattedDob\n";
        //         } else {
        //             $profileSummary .= "▪️ *$label*: {$profile->$field}\n";
        //         }
        //     }
        // }
        foreach ($orderedFields as $field) {
            if (!empty($profile->$field)) {
                $label = ucwords(str_replace('_', ' ', $field));

                if ($field === 'height') {
                    $cm = (int) $profile->height;
                    $ft = floor($cm / 30.48);
                    $remainingCm = $cm - ($ft * 30.48);
                    $in = round($remainingCm / 2.54);

                    $formattedHeight = "{$ft} ft {$in} in → {$cm} cm";
                    $profileSummary .= "▪️ *$label*: {$formattedHeight}\n";
                } elseif ($field === 'dob') {
                    $formattedDob = \Carbon\Carbon::parse($profile->dob)->format('d-m-Y');
                    $profileSummary .= "▪️ *$label*: $formattedDob\n";
                } else {
                    $profileSummary .= "▪️ *$label*: {$profile->$field}\n";
                }
            }
        }

        // Build preferences summary (only preference fields)
        // foreach ($preference->getAttributes() as $key => $value) {
        //     if (!in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at']) && !empty($value)) {
        //         $label = ucwords(str_replace('_', ' ', $key));
        //         $preferenceSummary .= "🔸 *$label*: $value\n";
        //     }
        // }
        $excluded = ['id', 'telegram_user_id', 'created_at', 'updated_at', 'profile_id'];
        $preferenceSummary = "\n*💘 Your Preferences:*\n";

        foreach ($preference->getAttributes() as $key => $value) {
            if (!in_array($key, $excluded) && !empty($value)) {
                $label = ucwords(str_replace('_', ' ', $key));

                // Format min and max partner height fields
                if (in_array($key, ['partner_min_height', 'partner_max_height'])) {
                    $cm = (int) $value;
                    $ft = floor($cm / 30.48);
                    $remainingCm = $cm - ($ft * 30.48);
                    $in = round($remainingCm / 2.54);
                    $value = "{$ft} ft {$in} in → {$cm} cm";
                }

                $preferenceSummary .= "🔸 *$label*: $value\n";
            }
        }

        $filename = ($gallery && $gallery->image_path)
            ? $gallery->image_path
            : 'profile_Pic.jpg'; // fallback default

        $photoPath = public_path("uploads/profiles/{$filename}");

        if (!file_exists($photoPath) || !is_readable($photoPath)) {
            $photoPath = public_path("uploads/profiles/profile_Pic.jpg"); // fallback local path
        }
        $this->sendPhoto($chatId, $photoPath, $profileSummary);
        // $this->sendPhoto($chatId, $photoPath, "👤 Here is your profile photo.");

        // $this->sendMessage($chatId, $profileSummary);

        // $this->sendMessage($chatId, $preferenceSummary);
        $this->sendMessage($chatId, $preferenceSummary, [
            'parse_mode' => 'Markdown',
            'reply_markup' => ['remove_keyboard' => true]
        ]);

        $matchController = app(\App\Http\Controllers\MatchController::class);
        return $matchController->findMatches($chatId, $profile);
    }

    private function getNextStep(string $currentStep, TelegramUserState $state): ?string
    {
        if ($currentStep === 'awaiting_diet') {
            if (($state->answers['diet'] ?? '') === 'Jain') {
                return 'awaiting_chovihar';
            } else {
                return 'awaiting_smoking';
            }
        }

        if ($currentStep === 'awaiting_partner_diet') {
            if (($state->answers['partner_diet'] ?? '') === 'Jain') {
                return 'awaiting_partner_chovihar';
            } else {
                return 'awaiting_partner_education_level';
            }
        }

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
        // $state->update([
        //     'current_step' => 'selecting_language',
        //     'answers' => [],
        //     'language' => null,
        // ]);

        // return $this->sendMessage($chatId, __('messages.language_select'), [
        //     'reply_markup' => json_encode([
        //         'keyboard' => [
        //             [['text' => 'English'], ['text' => 'हिन्दी']],
        //             [['text' => 'मराठी'], ['text' => 'ગુજરાતી']],
        //         ],
        //         'resize_keyboard' => true,
        //         'one_time_keyboard' => true,
        //     ])
        // ]);

        $state->update([
            'language' => 'en', // Or any default
            'current_step' => 'awaiting_name',
            'answers' => [],
        ]);

        return $this->sendMessage(
            $chatId,
            __('messages.registration_welcome') . "\n\n" . NameController::getQuestion(),
            NameController::getOptions()
        );
    }


    protected function handleLanguageSelection($chatId, $text, TelegramUserState $state)
    {
        $languages = [
            'English' => 'en',
            'हिन्दी' => 'hi',
            'मराठी' => 'mr',
            'ગુજરાતી' => 'gu',
        ];

        // ✅ Validate input
        if (!array_key_exists($text, $languages)) {
            return $this->sendMessage($chatId, __('messages.language_invalid'), [
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

        // ✅ Valid input, continue
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


    protected function handlePhotoUpload($chatId, $message, $text, $state, $controller, $handlers)
    {
        $skipCommands = [
            'en' => ['skip photo', 'done'],
            'hi' => ['फोटो छोड़ें', 'पूरा हुआ'],
            'mr' => ['फोटो वगळा', 'झाले'],
            'gu' => ['ફોટો છોડો', 'પૂર્ણ થયું'],
        ];

        $lang = $state->language ?? 'en';
        $allowedSkips = $skipCommands[$lang] ?? $skipCommands['en'];
        $textNormalized = mb_strtolower(trim($text));

        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $uploadedCount = $profile ? $profile->gallery()->count() : 0;

        // ✅ Handle "Upload another photo" button click
        if ($textNormalized === mb_strtolower(__('messages.upload_another_photo'))) {
            return $this->sendMessage(
                $chatId,
                __('messages.please_send_another_photo')
            );
        }

        // ✅ SKIP/DONE logic (only after at least 1 photo uploaded)
        if (in_array($textNormalized, array_map('mb_strtolower', $allowedSkips))) {
            if ($uploadedCount >= 1) {
                $nextStep = $this->getNextStep('awaiting_profile_photo', $state);
                $state->update(['current_step' => $nextStep]);

                $nextController = app($handlers[$nextStep]);

                return $this->sendMessage(
                    $chatId,
                    $nextController::getQuestion(),
                    method_exists($nextController, 'getOptions') ? $nextController::getOptions($state->answers ?? []) : [
                        'reply_markup' => json_encode(['remove_keyboard' => true])
                    ]
                );
            } else {
                return $this->sendMessage(
                    $chatId,
                    __('messages.profile_photo_required') // "Please upload at least one profile photo"
                );
            }
        }

        // ✅ HANDLE PHOTO UPLOAD
        if (isset($message['photo'])) {
            if ($uploadedCount >= 2) {
                return $this->sendMessage(
                    $chatId,
                    __('messages.profile_photo_limit_reached') // "You have already uploaded 2 photos"
                );
            }

            $response = $controller->handle($chatId, $message['photo'], $state);

            $uploadedCount++; // Local increment

            if ($uploadedCount === 1) {
                // ✅ First photo uploaded
                return $this->sendMessage(
                    $chatId,
                    __('messages.profile_photo_uploaded_first'),
                    [
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => __('messages.upload_another_photo')]],
                                [['text' => __('messages.skip_photo')]],
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => false,
                        ]),
                        'parse_mode' => 'Markdown'
                    ]
                );
            } elseif ($uploadedCount === 2) {
                // ✅ Second photo uploaded
                return $this->sendMessage(
                    $chatId,
                    __('messages.profile_photo_uploaded_second'),
                    [
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => __('messages.skip_photo')]]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => false,
                        ]),
                        'parse_mode' => 'Markdown'
                    ]
                );
            }
        }

        // ❌ Fallback for anything else
        return $this->sendMessage(
            $chatId,
            __('messages.profile_photo_invalid_text') // "Please upload a valid photo."
        );
    }


    protected function handleCallback(array $callback)
    {
        $callbackData = $callback['data'];
        $chatId = $callback['message']['chat']['id'];

        Log::info('🔘 Callback received', ['chat_id' => $chatId, 'data' => $callbackData]);

        $profile = Profile::where('telegram_user_id', $chatId)->first();

        // ✅ Next Match
        if ($callbackData === 'next_match') {
            $matchController = app(\App\Http\Controllers\MatchController::class);
            return $matchController->findMatches($chatId, $profile);
        }

        // ✅ Send Request
        if (str_starts_with($callbackData, 'send_request_')) {
            $matchId = str_replace('send_request_', '', $callbackData);
            //   Log::info("📌 Matching ID extracted", ['match_id' => $matchId]);
            return $this->handleSendRequest($chatId, $matchId);
        }


        if (str_starts_with($callbackData, 'approve_request_')) {
            [$prefix, $action, $senderId, $receiverId] = explode('_', $callbackData);

            Log::info('🔍 Approving request', compact('senderId', 'receiverId'));

            $matchRequest = MatchRequest::where('sender_id', $senderId)
                ->where('receiver_id', $receiverId)
                ->first();

            if (!$matchRequest) {
                Log::warning("❌ MatchRequest not found", [
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId
                ]);

                return $this->sendMessage($chatId, "❌ Match request not found.");
            }

            $matchRequest->status = 'approved';
            $matchRequest->save();

            $sender = Profile::find($senderId);
            $receiver = Profile::find($receiverId);

            // ✅ Notify sender
            $this->sendMessage(
                $sender->telegram_user_id,
                "🎉 *{$receiver->name}* has approved your request!",
                ['parse_mode' => 'Markdown']
            );

            // ✅ Notify receiver
            $this->sendMessage(
                $chatId,
                "✅ You have approved *{$sender->name}*'s request!",
                ['parse_mode' => 'Markdown']
            );
            app(TelegramController::class)->showOtherProfile(
                $sender->telegram_user_id,
                $receiver->id,
                true
            );

            $this->sendMessage(
                $chatId,
                "📞 *Phone Number of {$sender->name}*: {$sender->phone}, {$sender->email}",
                ['parse_mode' => 'Markdown']
            );
        }

        if (str_starts_with($callbackData, 'reject_request_')) {
            [$prefix, $action, $senderId, $receiverId] = explode('_', $callbackData);

            $receiver = Profile::where('telegram_user_id', $chatId)->first();

            $matchRequest = \App\Models\MatchRequest::where('sender_id', $senderId)
                ->where('receiver_id', $receiverId)
                ->first();

            if ($matchRequest) {
                $matchRequest->status = 'rejected';
                $matchRequest->save();

                $sender = Profile::find($senderId);
                $receiverProfile = Profile::find($receiverId);

                app(TelegramController::class)->sendMessage(
                    $sender->telegram_user_id,
                    "❌ *{$receiverProfile->name}* rejected your request.",
                    ['parse_mode' => 'Markdown']
                );

                return $this->sendMessage(
                    $chatId,
                    "❌ You rejected *{$sender->name}*'s request.",
                    ['parse_mode' => 'Markdown']
                );
            }

            return $this->sendMessage($chatId, "❌ Match request not found.");
        }

        // ⬅️ Previous Matches
        if ($callbackData === 'previous_match') {
            $previousMatches = \App\Models\DailyMatch::where('telegram_user_id', $chatId)
                ->with('matchedProfile')
                ->latest('shown_at')
                ->get();

            if ($previousMatches->isEmpty()) {
                return $this->sendMessage($chatId, "❌ No previous profiles available.");
            }

            foreach ($previousMatches as $matchRecord) {
                $match = $matchRecord->matchedProfile;
                if (!$match) continue;

                $heightSummary = '';
                if (!empty($match->height)) {
                    $cm = (int) $match->height;
                    $ft = floor($cm / 30.48);
                    $remainingCm = $cm - ($ft * 30.48);
                    $in = round($remainingCm / 2.54);
                    $heightSummary = "{$ft} ft {$in} in → {$cm} cm";
                } else {
                    $heightSummary = 'N/A';
                }

                $summary = "*👤 Previous Match:*\n";
                $summary .= "▪️ *Name:* {$match->name}\n";
                $summary .= "▪️ *Gender:* {$match->gender}\n";
                $summary .= "▪️ *Caste:* {$match->caste}\n";
                $summary .= "▪️ *Height:* {$heightSummary}\n";
                $summary .= "▪️ *City:* {$match->city}\n";
                $summary .= "▪️ *State:* {$match->state}\n";

                $summary .= "▪️ *Religion:* {$match->religion}\n";
                $summary .= "▪️ *Education:* {$match->education_level} - {$match->education_field}\n";
                $summary .= "▪️ *Profession:* {$match->profession}\n";
                $summary .= "▪️ *Specific Profession:* {$match->specific_profession}\n";
                $summary .= "▪️ *Working Sector:* {$match->working_sector}\n";
                $summary .= "▪️ *Income Range:* {$match->income_range}\n";
                $summary .= "▪️ *Marital Status:* {$match->marital_status}\n";
                $summary .= "▪️ *DOB:* {$match->dob}\n";
                $summary .= "▪️ *Diet:* {$match->diet}\n";
                $summary .= "▪️ *Smoking:* {$match->smoking}\n";
                $summary .= "▪️ *Drinking:* {$match->drinking}\n";
                $summary .= "▪️ *Body Type:* {$match->body_type}\n";
                $summary .= "▪️ *Skin Tone:* {$match->skin_tone}\n";
                $summary .= "▪️ *Job Status:* {$match->job_status}\n";
                $summary .= "▪️ *Sub-Caste:* {$match->sub_caste}\n";
                $summary .= "▪️ *Chovihar:* {$match->chovihar}\n";
                $summary .= "▪️ *Birth Time:* {$match->birth_time}\n";
                $summary .= "▪️ *Birth Place:* {$match->birth_place}\n";
                $summary .= "▪️ *Native Place:* {$match->native_place}\n";

                $gallery = Gallery::where('profile_id', $match->id)
                    ->orderBy('created_at', 'asc')
                    ->first();

                $filename = ($gallery && $gallery->image_path)
                    ? $gallery->image_path
                    : 'profile_Pic.jpg';

                $senderProfile = Profile::where('telegram_user_id', $chatId)->first();
                $existingRequest = \App\Models\MatchRequest::where('sender_id', $senderProfile->id)
                    ->where('receiver_id', $match->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->first();

                $buttons = [];

                if (!$existingRequest) {
                    $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
                }

                $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];

                $photoPath = public_path("uploads/profiles/{$filename}");

                if (!file_exists($photoPath) || !is_readable($photoPath)) {
                    $photoPath = public_path("uploads/profiles/profile_Pic.jpg"); // fallback local path
                }

                $this->sendPhoto($chatId, $photoPath, $summary, [
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ]),
                ]);
            }

            return response('ok');
        }

        return response('ok');
    }


    public function handleSendRequest($chatId, $matchId)
    {
        $matchUser = Profile::find($matchId);
        $currentUser = Profile::where('telegram_user_id', $chatId)->first();

        if (!$matchUser || !$currentUser) {
            return $this->sendMessage($chatId, "❌ Unable to send request. User not found.");
        }

        if (empty($matchUser->telegram_user_id)) {
            return $this->sendMessage($chatId, "⚠️ This user has not connected their Telegram yet, so we cannot send the request.");
        }

        // Check if request already exists
        $alreadyRequested = MatchRequest::where('sender_id', $currentUser->id)
            ->where('receiver_id', $matchUser->id)
            ->exists();

        if ($alreadyRequested) {
            // Re-send notification
            $this->sendMessage($matchUser->telegram_user_id, "📩 Reminder: {$currentUser->name} has sent you a request!");
            return $this->sendMessage($chatId, "⚠️ You've already sent a request to *{$matchUser->name}*. We've reminded them again.", [
                'parse_mode' => 'Markdown'
            ]);
        }

        // Create new request
        MatchRequest::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $matchUser->id,
            'status' => 'pending',
        ]);

        // ✅ Send Telegram message directly instead of notify()
        $this->sendMessage($matchUser->telegram_user_id, "💌 You have a new request from *{$currentUser->name}*!", [
            'parse_mode' => 'Markdown'
        ]);

        return $this->sendMessage($chatId, "✅ Request sent to *{$matchUser->name}*!", [
            'parse_mode' => 'Markdown'
        ]);
    }


    public function showOtherProfile($chatId, $profileId, $revealContact = false)
    {
        $profile = Profile::find($profileId);
        $preference = Preference::where('telegram_user_id', $profile->telegram_user_id ?? null)->first();

        if (!$profile || !$preference) {
            return $this->sendMessage($chatId, "❌ Profile or preferences not found.");
        }
        $matchMessage = "🎉 *Good news!* We've found a new profile that matches your preferences. ✨\n\nYour daily dose of curated matches is ready to be explored.";
        $this->sendMessage($chatId, $matchMessage);
        // --------------------------
        // 📋 PART 1: Profile Summary
        // --------------------------
        $profileSummary = "*👤 Profile Details:*\n";
        $fields = [
            'name',
            // 'email',
            'bio',
            'gender',
            'marital_status',
            'dob',
            'state',
            'city',
            'mother_tongue',
            'religion',
            'caste',
            'sub_caste',
            'height',
            'education_level',
            'education_field',
            'job_status',
            'working_sector',
            'profession',
            // 'phone',
            'diet',
            'smoking',
            'drinking',
            'body_type',
            'skin_tone',
        ];

        if ($revealContact) {
            $fields[] = 'phone';
            $fields[] = 'email';
        }

        foreach ($fields as $field) {
            if (!empty($profile->$field)) {
                $label = ucwords(str_replace('_', ' ', $field));
                $value = $field === 'dob'
                    ? \Carbon\Carbon::parse($profile->dob)->format('d-m-Y')
                    : $profile->$field;
                $profileSummary .= "▪️ *$label*: $value\n";
            }
        }

        // --------------------------
        // 📋 PART 2: Preference Info
        // --------------------------
        $preferenceSummary = "*💘 Preferences:*\n";
        foreach ($preference->getAttributes() as $key => $value) {
            if (!in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at', 'profile_id']) && !empty($value)) {
                $label = ucwords(str_replace('_', ' ', $key));
                $preferenceSummary .= "🔸 *$label*: $value\n";
            }
        }

        // --------------------------
        // 🖼️ Image Handling
        // --------------------------
        $galleryImages = Gallery::where('profile_id', $profileId)
            ->orderBy('id', 'asc')
            ->take(2)
            ->get();

        if ($galleryImages->isEmpty()) {
            // No images: use fallback
            $this->sendPhoto(
                $chatId,
                public_path('uploads/profiles/profile_Pic.jpg'),
                $profileSummary,
                ['parse_mode' => 'Markdown']
            );
            $this->sendMessage($chatId, $preferenceSummary, ['parse_mode' => 'Markdown']);
            return;
        }

        // First image with profile details
        $firstImagePath = public_path('uploads/profiles/' . $galleryImages[0]->image_path);
        if (!file_exists($firstImagePath)) {
            $firstImagePath = public_path('uploads/profiles/profile_Pic.jpg');
        }

        $this->sendPhoto(
            $chatId,
            $firstImagePath,
            $profileSummary,
            ['parse_mode' => 'Markdown']
        );

        // Send preferences as separate message
        $this->sendMessage($chatId, $preferenceSummary, ['parse_mode' => 'Markdown']);

        // Send second image if available
        if ($galleryImages->count() > 1) {
            $secondImagePath = public_path('uploads/profiles/' . $galleryImages[1]->image_path);
            if (file_exists($secondImagePath)) {
                $this->sendPhoto($chatId, $secondImagePath); // no caption
            }
        }
    }
}
