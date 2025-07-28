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
    PartnerJobStatusController,
    PartnerMinAgeController,
    PartnerMaxAgeController,
    PartnerMinHeightController,
    PartnerMaxHeightController,
    // PartnerGenderController,
    PartnerLanguageController,
    PartnerReligionController
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

            $matchController = app(\App\Http\Controllers\MatchController::class);
            return $matchController->findMatches($chatId, $profile);
        }

             if (strtolower($text) === '/approved') {
                $profile = Profile::where('telegram_user_id', $chatId)->first();

                // if (!$profile) {
                //     return $this->sendMessage($chatId, "❌ You need to create a profile first using /start.");
                // }

                // Fetch all approved requests involving this user
                $approvedRequests = MatchRequest::where('status', 'approved')
                    ->where(function ($query) use ($profile) {
                        $query->where('sender_id', $profile->id)
                            ->orWhere('receiver_id', $profile->id);
                    })
                    ->get();

                if ($approvedRequests->isEmpty()) {
                    return $this->sendMessage($chatId, "😕 You haven't approved or been approved by any users yet.");
                }

                // Collect other profiles (either sender or receiver)
                foreach ($approvedRequests as $request) {
                    $otherProfile = $request->sender_id == $profile->id
                        ? Profile::find($request->receiver_id)
                        : Profile::find($request->sender_id);

                    if (!$otherProfile) continue;

                    $summary = "*❤️ Approved Match:*\n";
                    $summary .= "▪️ *Name:* {$otherProfile->name}\n";
                    $summary .= "▪️ *Gender:* {$otherProfile->gender}\n";
                    $summary .= "▪️ *Caste:* {$otherProfile->caste}\n";
                    $summary .= "▪️ *Height:* {$otherProfile->height}\n";
                    $summary .= "▪️ *City:* {$otherProfile->city}\n";
                    $summary .= "▪️ *Phone:* {$otherProfile->phone}\n";
                    $summary .= "▪️ *Email:* {$otherProfile->email}\n";

                    $photo = $otherProfile->profile_photo ?? 'profile_Pic.jpg';
                    $photoUrl = asset('uploads/profiles/' . $photo);

                    $this->sendPhoto($chatId, $photoUrl);
                    $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
                }

                return response('ok');
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
            'awaiting_bio' => BioController::class,
            'awaiting_email' => EmailController::class,
            'awaiting_gender' => GenderController::class,
            'awaiting_mobile' => MobileController::class,
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
            'awaiting_income_range' => IncomeRangeController::class,
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
            'awaiting_partner_gender' => PartnerReligionController::class,
            'awaiting_partner_gender' => PartnerJobStatusController::class,
            'awaiting_partner_income_range' => PartnerIncomeRangeController::class,
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

                $summary = "*👤 Previous Match:*\n";
                $summary .= "▪️ *Name:* {$match->name}\n";
                $summary .= "▪️ *Gender:* {$match->gender}\n";
                $summary .= "▪️ *Caste:* {$match->caste}\n";
                $summary .= "▪️ *Height:* {$match->height} ft\n";
                $summary .= "▪️ *City:* {$match->city}\n";
                // $summary .= "▪️ *Phone:* {$match->phone}\n";

                // 📸 Send photo
                $image = $match->profile_photo ?? 'profile_Pic.jpg';
                $photoUrl = asset('uploads/profiles/' . $image);
                $this->sendPhoto($chatId, $photoUrl);
                $senderProfile = Profile::where('telegram_user_id', $chatId)->first();

                // ✅ Check if request already sent
                $existingRequest = \App\Models\MatchRequest::where('sender_id', $senderProfile->id)
                    ->where('receiver_id', $match->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->first();

                $buttons = [];

                if (!$existingRequest) {
                    $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
                }

                $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];

                $this->sendMessage($chatId, $summary, [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
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
            return $this->sendMessage($chatId, "❌ Unable to send request.");
        }

        $alreadyRequested = MatchRequest::where('sender_id', $currentUser->id) // ✅ Use profile.id
            ->where('receiver_id', $matchUser->id)
            ->exists();

        if ($alreadyRequested) {
            return $this->sendMessage($chatId, "⚠️ You've already sent a request to {$matchUser->name}.");
        }

        // Save to DB
        MatchRequest::create([
            'sender_id' => $currentUser->id,  // Not telegram ID
            'receiver_id' => $matchUser->id,
            'status' => 'pending',
        ]);

        // Send Telegram notification
        $matchUser->notify(new RequestReceivedNotification($currentUser));

        return $this->sendMessage($chatId, "✅ Request sent to *{$matchUser->name}*!", [
            'parse_mode' => 'Markdown'
        ]);
    }

   
    public function showOtherProfile($chatId, $profileId, $revealContact = false)
    {
        $profile = Profile::find($profileId);
        $preference = Preference::where('telegram_user_id', $profile->telegram_user_id ?? null)->first();
        $gallery = Gallery::where('profile_id', $profileId)->latest('created_at')->first();

        if (!$profile || !$preference) {
            return $this->sendMessage($chatId, "❌ Profile or preferences not found.");
        }

        $summary = "*👤 Profile Details:*\n";

        $fields = [
            'name',
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
            'job_status',
            'profession',
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
                $summary .= "▪️ *$label*: $value\n";
            }
        }

        $summary .= "\n*💘 Preferences:*\n";
        foreach ($preference->getAttributes() as $key => $value) {
            if (!in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at']) && !empty($value)) {
                $label = ucwords(str_replace('_', ' ', $key));
                $summary .= "🔸 *$label*: $value\n";
            }
        }

        $filename = $gallery->image_path ?? 'profile_Pic.jpg';
        $photoPath = public_path('uploads/profiles/' . $filename);

        $this->sendPhoto(
            $chatId,
            file_exists($photoPath) ? $photoPath : secure_asset('uploads/profiles/' . $filename),
            $summary,
            ['parse_mode' => 'Markdown']
        );
    }
}