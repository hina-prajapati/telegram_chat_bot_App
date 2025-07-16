<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    TelegramUserState,
    TelegramMessage,
    Profile,
    Preference,
    Gallery
};
use Illuminate\Support\Facades\{Log, Http};
use App\Http\Controllers\Profile\{
    NameController,
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
    GenderController
};
use App\Http\Controllers\Preference\{
    PartnerMaritalStatusController,
    PartnerCasteController,
    PartnerMinAgeController,
    PartnerMaxAgeController,
    PartnerMinHeightController,
    PartnerMaxHeightController,
    PartnerGenderController,
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
            $callbackData = $update['callback_query']['data'];
            $chatId = $update['callback_query']['message']['chat']['id'];

            Log::info('🔘 Callback received', ['chat_id' => $chatId, 'data' => $callbackData]);

            $profile = Profile::where('telegram_user_id', $chatId)->first();
            if ($callbackData === 'next_match') {
                return $this->handleNextMatch($chatId, $profile);
            }

            return response('ok');
        }

        // ✅ Handle incoming message
        $message = $update['message'] ?? null;
        if (!$message) {
            Log::warning('⚠️ No message found in update.');
            return response('ok');
        }

        $chatId = $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        Log::info('👤 Message received', ['chat_id' => $chatId, 'text' => $text]);

        $state = TelegramUserState::firstOrCreate(
            ['telegram_user_id' => $chatId],
            ['current_step' => null, 'answers' => []]
        );
        Log::info('👤 Message received', ['state', $state]);

        $handlers = $this->getHandlers();
        $currentStep = $state->current_step;
        $answers = $state->answers ?? [];

        // ✅ Handle /start
        if (strtolower($text) === '/start') {
            Log::info('🚀 /start command received');

            if ($this->hasCompletedProfile($chatId) && !$state->current_step) {
                // Log::info('✅ Profile already completed. Showing profile.');
                $state->update(['current_step' => null, 'answers' => []]);
                return $this->showProfile($chatId);
            }

            $profile = Profile::where('telegram_user_id', $chatId)->first();
            $preference = Preference::where('telegram_user_id', $chatId)->first();

            if ($profile && $preference && !$state->current_step) {
                // Log::info('✅ Profile already completed. Showing profile.');
                return $this->showProfile($chatId);
            }

            if (!$profile || empty($profile->name)) {
                // Log::info('🆕 Starting new registration.');
                $state->update(['current_step' => 'awaiting_name', 'answers' => []]);

                return $this->sendMessage(
                    $chatId,
                    "💖 *Welcome to LoveConnect!* 💖\n\n" .
                        "We're excited to be part of your beautiful journey together. Let's start by knowing a bit about you!\n\n" .
                        NameController::getQuestion(),
                    NameController::getOptions()
                );
            }

            // 🔁 Resume from saved step id new user
            $step = $state->current_step ?? 'awaiting_name';
            if (isset($handlers[$step])) {
                $controllerClass = $handlers[$step];
                return $this->sendMessage(
                    $chatId,
                    $controllerClass::getQuestion(),
                    method_exists($controllerClass, 'getOptions') ? $controllerClass::getOptions($answers) : []
                );
            }

            return $this->sendMessage($chatId, "⏳ Let's continue your profile.");
        }

        // ✅ Handle known step
        if (isset($handlers[$currentStep])) {
            // Log::info("🧭 Handling step: $currentStep");
            $controller = app($handlers[$currentStep]);

            // 📸 Handle special photo step
            if ($currentStep === 'awaiting_profile_photo') {
                if (in_array(strtolower($text), ['skip photo', 'done'])) {
                    $nextStep = $this->getNextStep($currentStep);
                    $state->update(['current_step' => $nextStep]);

                    $nextController = app($handlers[$nextStep]);
                    return $this->sendMessage(
                        $chatId,
                        $nextController::getQuestion(),
                        method_exists($nextController, 'getOptions') ? $nextController::getOptions($answers) : []
                    );
                }

                if (isset($message['photo'])) {
                    Log::info('📸 Handling profile photo upload');
                    $response = $controller->handle($chatId, $message['photo'], $state);
                    return $this->sendStructuredMessage($chatId, $response);
                }

                return $this->sendMessage($chatId, "📸 Please send a valid photo or type *Done* to continue.");
            }

            // ✍️ Handle regular input
            $response = $controller->handle($chatId, $text, $state);
            $this->sendStructuredMessage($chatId, $response);

            // ➡️ Move to next step
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
                // 🎉 Completed all steps
                $state->update(['current_step' => null]);
                return $this->showProfile($chatId, $state->answers ?? []);
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
            'awaiting_partner_gender' => PartnerGenderController::class,
            'awaiting_partner_min_height' => PartnerMinHeightController::class,
            'awaiting_partner_max_height' => PartnerMaxHeightController::class,
            'awaiting_partner_language' => PartnerLanguageController::class,
        ];
    }

    public function handleNextMatch($chatId, $user)
    {
        // Fetch preferences
        $preference = Preference::where('telegram_user_id', $user->telegram_user_id)->first();

        if (!$preference) {
            Log::warning("⚠️ No preferences found for user ID {$user->telegram_user_id}");
            return $this->sendMessage($chatId, "❌ Please complete your preferences first.");
        }

        $targetGender = $preference->partner_gender ?? ($user->gender === 'Male' ? 'Female' : 'Male');

        $offsetKey = "match_offset_user_" . $user->id;
        $offset = cache()->get($offsetKey, 0);

        $matches = Profile::where('gender', $targetGender)
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($preference) {
                $hasAnyPreference = false;

                $query->where(function ($q) use ($preference, &$hasAnyPreference) {
                    if (!empty($preference->partner_marital_status)) {
                        $q->orWhere('marital_status', $preference->partner_marital_status);
                        $hasAnyPreference = true;
                    }
                    if (!empty($preference->partner_language)) {
                        $q->orWhere('mother_tongue', $preference->partner_language);
                        $hasAnyPreference = true;
                    }
                    if (!empty($preference->partner_caste)) {
                        $q->orWhere('caste', $preference->partner_caste);
                        $hasAnyPreference = true;
                    }
                    if (!empty($preference->partner_max_height)) {
                        $q->orWhere('height', '<=', $preference->partner_max_height);
                        $hasAnyPreference = true;
                    }
                    if (!empty($preference->partner_min_height)) {
                        $q->orWhere('height', '>=', $preference->partner_min_height);
                        $hasAnyPreference = true;
                    }
                });

                if (!$hasAnyPreference) {
                    $query->whereRaw('1 = 0'); 
                }
            })
            ->orderBy('id')
            ->skip($offset)
            ->take(1) 
            ->get();

        if ($matches->isEmpty()) {
            cache()->forget($offsetKey);
            return $this->sendMessage($chatId, "😕 No more matching profiles found.");
        }

        foreach ($matches as $match) {
            $caption = "👤 *{$match->name}*\n"
                . "📱 Phone: {$match->phone}\n"
                . "💍 Marital Status: {$match->marital_status}\n"
                . "🏞️ State: {$match->state}\n"
                . "🏙️ City: {$match->city}\n"
                . "🗣️ Mother Tongue: {$match->mother_tongue}\n"
                . "👀 Height: {$match->height}";

            if ($match->profile_photo) {
                $this->sendPhoto(
                    $chatId,
                    asset('uploads/profiles/' . $match->profile_photo),
                    $caption,
                    ['parse_mode' => 'Markdown']
                );
            } else {
                $this->sendMessage($chatId, $caption, ['parse_mode' => 'Markdown']);
            }
        }

        // Increment offset by 1
        cache()->put($offsetKey, $offset + 1, now()->addMinutes(30));

        return $this->sendMessage(
            $chatId,
            "⏭️ Want more matches?",
            [
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']]
                    ]
                ])
            ]
        );
    }

    private function hasCompletedProfile($chatId)
    {
        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $preference = Preference::where('telegram_user_id', $chatId)->first();

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
            'gender'
        ];

        $requiredPreferenceFields = [
            'partner_marital_status',
            'partner_caste',
            'partner_min_age',
            'partner_max_age',
            'partner_min_height',
            'partner_max_height',
            'partner_gender',
            'partner_language'
        ];

        foreach ($requiredProfileFields as $field) {
            if (empty($profile[$field])) return false;
        }

        foreach ($requiredPreferenceFields as $field) {
            if (empty($preference[$field])) return false;
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

    private function sendMessage($chatId, $text, $options = [])
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

    private function showProfile($chatId)
    {
        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $preference = Preference::where('telegram_user_id', $chatId)->first();
        $gallery = Gallery::where('profile_id', $profile->id)
            ->latest('created_at') // or ->orderBy('created_at', 'desc')
            ->first();

        if (!$profile || !$preference) {
            return $this->sendMessage($chatId, "❌ Profile or preferences not found.");
        }

        // ✅ Build summary
        $summary = "*👤 Your Profile:*\n";
        // foreach ($profile->getAttributes() as $key => $value) {
        //     if (!in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at'])) {
        //         $label = ucwords(str_replace('_', ' ', $key));
        //         $summary .= "▪️ *$label*: $value\n";
        //     }
        // }
        $orderedFields = [
            'name',
            'email',
            'gender', // placed immediately after email
            'marital_status',
            'dob',
            'state',
            'city',
            'mother_tongue',
            'religion',
            'caste',
            'education_level',
            'education_field',
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
            if (!in_array($key, ['id', 'telegram_user_id', 'created_at', 'updated_at'])) {
                $label = ucwords(str_replace('_', ' ', $key));
                $summary .= "🔸 *$label*: $value\n";
            }
        }

        if ($gallery) {
            $filename = $gallery->image_path;
            $photoPath = public_path('uploads/profiles/' . $filename);

            if (file_exists($photoPath)) {
                $this->sendPhoto($chatId, $photoPath, $summary, ['parse_mode' => 'Markdown']);
            } else {
                $photoUrl = secure_asset('uploads/profiles/' . $filename);
                $this->sendPhoto($chatId, $photoUrl, $summary, ['parse_mode' => 'Markdown']);
            }
        } else {
            $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
        }
        // ✅ Now show match button
        return $this->handleNextMatch($chatId, $profile);
    }

    private function getNextStep(string $currentStep): ?string
    {
        $steps = array_keys($this->getHandlers());
        $index = array_search($currentStep, $steps);
        return $steps[$index + 1] ?? null;
    }
}
