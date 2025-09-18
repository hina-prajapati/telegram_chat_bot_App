<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gallery;
use App\Models\Profile;
use App\Models\DailyMatch;
use App\Models\Preference;
use App\Models\MatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Profile\TelegramController;
use App\Http\Controllers\Profile\IncomeRangeController;

class MatchController extends TelegramController
{

    // next match previous match
    // public function findMatches($chatId)
    // {
    //     // Step 1: Get preference
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
    //     }

    //     // Step 2: Get user profile
    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
    //     }

    //     $userGender = strtolower($userProfile->gender);
    //     $oppositeGender = $userGender === 'male' ? 'female' : 'male';

    //     // Step 3: Get already matched IDs
    //     $shownIds = DailyMatch::where('telegram_user_id', $chatId)->pluck('matched_user_id')->toArray();

    //     // Step 4: Build query
    //     try {
    //         $query = $this->buildQuery($preference, $chatId, $oppositeGender)
    //             ->whereNotIn('id', $shownIds);
    //     } catch (\Exception $e) {
    //         return $this->sendMessage($chatId, "❌ Error building match query.");
    //     }

    //     // Step 5: Get next match
    //     $match = $query->first();

    //     if (!$match) {
    //         // If no more new match, check if any shown matches exist for Previous
    //         $lastMatch = DailyMatch::where('telegram_user_id', $chatId)
    //             ->latest('shown_at')
    //             ->with('matchedProfile') // assuming you have relation set up
    //             ->first();

    //         $buttons = [];

    //         if ($lastMatch && $lastMatch->matchedProfile) {
    //             $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
    //         }

    //         return $this->sendMessage($chatId, "😔 No more matches found.", [
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ])
    //         ]);
    //     }

    //     // Step 6: Save this match to DailyMatch
    //     DailyMatch::create([
    //         'telegram_user_id' => $chatId,
    //         'matched_user_id' => $match->id,
    //         'shown_at' => now(),
    //     ]);

    //     // Step 7: Build match summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} ft\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     // $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     // Step 8: Send image and message
    //     try {
    //         $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //         $path = public_path('uploads/profiles/' . $image);

    //         if (file_exists($path)) {
    //             $this->sendPhoto($chatId, $path);
    //         }
    //         $senderProfile = Profile::where('telegram_user_id', $chatId)->first();

    //         $existingRequest = MatchRequest::where('sender_id', $senderProfile->id)
    //             ->where('receiver_id', $match->id)
    //             ->whereIn('status', ['pending', 'approved'])
    //             ->first();

    //         $buttons = [];

    //         if ($existingRequest) {
    //             if ($existingRequest->status === 'approved') {
    //                 $buttons[] = [['text' => '✅ Request Approved', 'callback_data' => 'noop']];
    //             } elseif ($existingRequest->status === 'pending') {
    //                 $buttons[] = [['text' => '⏳ Request Pending', 'callback_data' => 'noop']];
    //             }
    //         } else {
    //             $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
    //         }

    //         $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];


    //         $this->sendMessage($chatId, $summary, [
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ])
    //         ]);
    //         // cache()->forget("previous_index_user_{$chatId}");

    //     } catch (\Exception $e) {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }
    // }
    public function findMatches($chatId)
    {
        $preference = Preference::where('telegram_user_id', $chatId)->first();
        if (!$preference) {
            return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
        }

        $userProfile = Profile::where('telegram_user_id', $chatId)->first();
        if (!$userProfile || empty($userProfile->gender)) {
            return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
        }

        $userGender = strtolower($userProfile->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        $shownIds = DailyMatch::where('telegram_user_id', $chatId)->pluck('matched_user_id')->toArray();

        // ----------- LEVEL 1: Strict Preference Match -----------
        try {
            $profiles = $this->buildQuery($preference, $chatId, $oppositeGender);
            // Filter out already shown matches
            // $profiles = $profiles->whereNotIn('id', $shownIds)->values();
            $profiles = $profiles->reject(fn($p) => in_array($p->id, $shownIds))->values();

            // $profiles = $this->buildQuery($preference, $chatId, $oppositeGender)
            //     ->whereNotIn('id', $shownIds)
            //     ->get()
            //     ->values();
        } catch (\Exception $e) {
            Log::error('Match Query Error', [
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendMessage($chatId, "❌ Error building match query.");
        }

        $match = $profiles->first();
        if ($match) {
            $matchMessage = "🎯 Profile found based on your complete preferences.";
        }
        // ----------- LEVEL 2: Gender, Age, Height Only -----------


        if (!$match) {

            Log::info('Running match 2 : Gender, Age, Height, and Income', [
                'chat_id' => $chatId,
                'opposite_gender' => $oppositeGender,
                'min_age' => $preference->partner_min_age,
                'max_age' => $preference->partner_max_age,
                'min_height' => $preference->partner_min_height,
                'max_height' => $preference->partner_max_height,
                'income_range' => $preference->partner_income_range,
            ]);
            $query = Profile::query()
                ->where('id', '!=', $userProfile->id)
                ->whereRaw('LOWER(gender) = ?', [$oppositeGender])
                ->whereNotIn('id', $shownIds);

            if ($preference->partner_min_age) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
            }
            if ($preference->partner_max_age) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
            }
            if ($preference->partner_min_height) {
                $query->where('height', '>=', $preference->partner_min_height);
            }
            if ($preference->partner_max_height) {
                $query->where('height', '<=', $preference->partner_max_height);
            }

            // Religion
            if ($preference->partner_religion && strtolower($preference->partner_religion) !== 'any') {
                $query->where(function ($q) use ($preference) {
                    $q->where('religion', $preference->partner_religion)
                        ->orWhere('religion', 'Other');
                });
            }

            // Caste
            if ($preference->partner_caste && strtolower($preference->partner_caste) !== 'any') {
                $query->where(function ($q) use ($preference) {
                    $q->where('caste', $preference->partner_caste)
                        ->orWhere('caste', 'Other');
                });
            }
            $results = $query->get();

            if ($preference->partner_income_range !== 'Any') {
                $incomeHelper = new IncomeRangeController();
                $prefIncome = $incomeHelper->getMinMax($preference->partner_income_range);

                $results = $results->filter(function ($profile) use ($incomeHelper, $prefIncome) {
                    $profileIncome = $incomeHelper->getMinMax($profile->income_range);
                    return $profileIncome && $prefIncome &&
                        $profileIncome['max'] >= $prefIncome['min'] &&
                        $profileIncome['min'] <= $prefIncome['max'];
                });
            }

            $match = $results->first();
            if ($match) {
                $matchMessage = "🔍 Profile found based on some of your preferences (e.g., gender, age, height, religion, caste and income).";
            }
        }

        // ----------- LEVEL 3: Gender + Location (State/City) -----------
        // if (!$match) {
        //     Log::info('Running 3nd level match:', [
        //         'chat_id' => $chatId,
        //         'opposite_gender' => $oppositeGender,
        //     ]);
        //     $query = Profile::query()
        //         ->where('telegram_user_id', '!=', $chatId)
        //         ->whereRaw('LOWER(gender) = ?', [$oppositeGender])
        //         // ->where('state', $userProfile->state)
        //         ->whereNotIn('id', $shownIds);

        //     // Optionally, also filter by city
        //     // ->where('city', $userProfile->city);

        //     $match = $query->first();
        // }

        // ----------- No Match Found -----------
        if (!$match) {
            $lastMatch = DailyMatch::where('telegram_user_id', $chatId)
                ->latest('shown_at')
                ->with('matchedProfile')
                ->first();

            $buttons = [];
            if ($lastMatch && $lastMatch->matchedProfile) {
                $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
            }

            // return $this->sendMessage($chatId, "😔 No more matches found.", [
            //     'reply_markup' => json_encode([
            //         'inline_keyboard' => $buttons
            //     ])
            // ]);
            return $this->sendMessage($chatId, "🔍 Hang in there! We're continuously searching for a perfect match for you.", [ // subtle placeholder
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ])
            ]);
        }

        // ----------- Save this match to DailyMatch -----------
        DailyMatch::create([
            'telegram_user_id' => $chatId,
            'matched_user_id' => $match->id,
            'shown_at' => now(),
        ]);

        // ----------- Build match summary -----------
        // $summary = "*👤 Match Found:*\n";
        // $summary .= $matchMessage . "\n\n"; // ✅ First time is fine
        // $summary .= "▪️ *Name:* {$match->name}\n";
        // $summary .= "▪️ *Gender:* {$match->gender}\n";
        // $summary .= "▪️ *Caste:* {$match->caste}\n";
        // $summary .= "▪️ *Height:* {$match->height} ft\n";
        // $summary .= "▪️ *City:* {$match->city}\n";

        $summary = "*👤 Match Found:*\n";
        $summary .= $matchMessage . "\n\n";

        // Convert height (cm) to feet & inches if height is set
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

        $summary .= "▪️ *Name:* {$match->name}\n";
        $summary .= "▪️ *Gender:* {$match->gender}\n";
        $summary .= "▪️ *Caste:* {$match->caste}\n";
        $summary .= "▪️ *Height:* {$heightSummary}\n";
        $summary .= "▪️ *City:* {$match->city}\n";

        // Add other fields if you want:
        $otherFields = [
            // 'email',
            'marital_status',
            'dob',
            'state',
            'mother_tongue',
            'religion',
            'education_level',
            'education_field',
            'working_sector',
            'profession',
            // 'phone',
            'diet',
            'smoking',
            'drinking',
            'body_type',
            'skin_tone',
            'job_status',
            'bio',
            'income_range',
            'sub_caste',
            'specific_profession',
            'chovihar',
            'birth_time',
            'birth_place',
            'native_place'
        ];

        foreach ($otherFields as $field) {
            if (!empty($match->$field)) {
                $label = ucwords(str_replace('_', ' ', $field));
                $value = $match->$field;

                // Format dob if found
                if ($field === 'dob') {
                    $value = \Carbon\Carbon::parse($value)->format('d-m-Y');
                }

                $summary .= "▪️ *$label:* $value\n";
            }
        }

        // ----------- Send image and message -----------
        try {
            $image = $match->profile_photo ?? 'profile_Pic.jpg';
            $path = public_path('uploads/profiles/' . $image);

            $senderProfile = Profile::where('telegram_user_id', $chatId)->first();
            $gallery = Gallery::where('profile_id', $match->id)
                ->latest('created_at')
                ->first();

            $filename = ($gallery && $gallery->image_path)
                ? $gallery->image_path
                : 'profile_Pic.jpg';
            $existingRequest = MatchRequest::where(function ($q) use ($senderProfile, $match) {
                $q->where('sender_id', $senderProfile->id)
                    ->where('receiver_id', $match->id);
            })->orWhere(function ($q) use ($senderProfile, $match) {
                $q->where('sender_id', $match->id)
                    ->where('receiver_id', $senderProfile->id);
            })
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
            $photoPath = public_path("uploads/profiles/{$filename}");

            if (!file_exists($photoPath) || !is_readable($photoPath)) {
                $photoPath = public_path("uploads/profiles/profile_Pic.jpg"); // fallback local path
            }

            $this->sendPhoto($chatId, $photoPath, $summary, [
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ]),
            ]);
        } catch (\Exception $e) {
            $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
        }
    }


    // private function buildQuery($preference, $excludeUserId, $matchGender)
    // {
    //     $query = Profile::query()
    //         ->where('telegram_user_id', '!=', $excludeUserId)
    //         ->whereRaw('LOWER(gender) = ?', [$matchGender]);

    //     $profile = Profile::where('telegram_user_id', $excludeUserId)->first();

    //     if ($preference->partner_min_height) {
    //         $query->where('height', '>=', $preference->partner_min_height);
    //     }
    //     if ($preference->partner_max_height) {
    //         $query->where('height', '<=', $preference->partner_max_height);
    //     }
    //     if ($preference->partner_min_age) {
    //         $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
    //     }
    //     if ($preference->partner_max_age) {
    //         $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
    //     }

    //     if (
    //         $preference->partner_marital_status &&
    //         strtolower($preference->partner_marital_status) !== 'any'
    //     ) {
    //         $query->where('marital_status', $preference->partner_marital_status);
    //     }

    //     if (
    //         $preference->partner_religion &&
    //         strtolower($preference->partner_religion) !== 'any'
    //     ) {
    //         $query->where(function ($q) use ($preference) {
    //             $q->where('religion', $preference->partner_religion)
    //                 ->orWhere('religion', 'Other');
    //         });
    //     }

    //     if (
    //         $preference->partner_caste &&
    //         strtolower($preference->partner_caste) !== 'any'
    //     ) {
    //         $query->where(function ($q) use ($preference) {
    //             $q->where('caste', $preference->partner_caste)
    //                 ->orWhere('caste', 'Other'); // allow "Other" caste to pass
    //         });
    //     }

    //     if (
    //     $preference->partner_education_level &&
    //     strtolower($preference->partner_education_level) !== 'any'
    //     ) {
    //         $query->where(function ($q) use ($preference) {
    //             $q->where('education_level', $preference->partner_education_level)
    //             ->orWhere('education_level', 'Other'); // Allow profiles with "Other"
    //         });
    //     }

    //  if (
    //         $preference->partner_religion &&
    //         strtolower($preference->partner_religion) !== 'any'
    //     ) {
    //         $query->where(function ($q) use ($preference) {
    //             $q->where('religion', $preference->partner_religion)
    //                 ->orWhere('religion', 'Other');
    //         });
    //     }

    //     if (
    //     $preference->partner_job_status &&
    //     strtolower($preference->partner_job_status) !== 'any'
    //     ) {
    //         $query->where(function ($q) use ($preference) {
    //             $q->where('job_status', $preference->partner_job_status)
    //             ->orWhere('job_status', 'Other');
    //         });
    //     }

    //     Log::info('SQL:', [$query->toSql()]);
    //     Log::info('Bindings:', [$query->getBindings()]);

    //     // Fetch all profiles matching other filters
    //     $profiles = $query->with('galleries')->get();

    //     // Filter by income range overlap in PHP
    //     if ($preference->partner_income_range && $preference->partner_income_range !== 'Any') {
    //         $incomeHelper = new \App\Http\Controllers\Profile\IncomeRangeController();
    //         $prefIncome = $incomeHelper->getMinMax($preference->partner_income_range);

    //         $profiles = $profiles->filter(function ($profile) use ($incomeHelper, $prefIncome) {
    //             $profileIncome = $incomeHelper->getMinMax($profile->income_range);

    //             $overlap = $profileIncome && $prefIncome
    //                 ? ($profileIncome['max'] >= $prefIncome['min'] && $profileIncome['min'] <= $prefIncome['max'])
    //                 : false;

    //             Log::info('Income Range Debug first', [
    //                 'profile_id' => $profile->id,
    //                 'profile_income' => $profile->income_range,
    //                 'profile_min' => $profileIncome['min'] ?? null,
    //                 'profile_max' => $profileIncome['max'] ?? null,
    //                 'pref_min' => $prefIncome['min'] ?? null,
    //                 'pref_max' => $prefIncome['max'] ?? null,
    //                 'overlap' => $overlap ? 'MATCH' : 'NO MATCH'
    //             ]);

    //             return $overlap;
    //         });
    //     }

    //     // return $query;
    //     return $profiles;
    // }

    private function buildQuery($preference, $excludeUserId, $matchGender)
    {
        $query = Profile::query()
            ->where('telegram_user_id', '!=', $excludeUserId)
            ->whereRaw('LOWER(gender) = ?', [$matchGender]);

        // Height & Age filters
        if ($preference->partner_min_height) {
            $query->where('height', '>=', $preference->partner_min_height);
        }
        if ($preference->partner_max_height) {
            $query->where('height', '<=', $preference->partner_max_height);
        }
        if ($preference->partner_min_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
        }
        if ($preference->partner_max_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
        }

        // Marital Status
        if ($preference->partner_marital_status && strtolower($preference->partner_marital_status) !== 'any') {
            $query->where('marital_status', $preference->partner_marital_status);
        }

        // Religion
        if ($preference->partner_religion && strtolower($preference->partner_religion) !== 'any') {
            $query->where(function ($q) use ($preference) {
                $q->where('religion', $preference->partner_religion)
                    ->orWhere('religion', 'Other');
            });
        }

        // Caste
        if ($preference->partner_caste && strtolower($preference->partner_caste) !== 'any') {
            $query->where(function ($q) use ($preference) {
                $q->where('caste', $preference->partner_caste)
                    ->orWhere('caste', 'Other');
            });
        }

        // Education Level
        if ($preference->partner_education_level && strtolower($preference->partner_education_level) !== 'any') {
            $query->where(function ($q) use ($preference) {
                $q->where('education_level', $preference->partner_education_level)
                    ->orWhere('education_level', 'Other');
            });
        }

        // Job Status
        if ($preference->partner_job_status && strtolower($preference->partner_job_status) !== 'any') {
            $query->where(function ($q) use ($preference) {
                $q->where('job_status', $preference->partner_job_status)
                    ->orWhere('job_status', 'Other');
            });
        }
        // profession
        if ($preference->profession && strtolower($preference->profession)) {
            $query->where(function ($q) use ($preference) {
                $q->where('profession', $preference->profession)
                    ->orWhere('profession', 'Other');
            });
        }


        if ($preference->specific_profession && strtolower($preference->specific_profession)) {
            $query->where(function ($q) use ($preference) {
                $q->where('specific_profession', $preference->specific_profession)
                    ->orWhere('specific_profession', 'Other');
            });
        }

        // 🍽️ Diet + Chovihar Logic
        if ($preference->partner_diet && strtolower($preference->partner_diet) !== 'any') {
            $query->where('diet', $preference->partner_diet);

            // 🪷 Only check chovihar if diet is Jain and user specified a preference
            if (
                strtolower($preference->partner_diet) === 'jain' &&
                isset($preference->partner_chovihar) &&
                strtolower($preference->partner_chovihar) !== "doesn't matter"
            ) {
                $query->where('chovihar', $preference->partner_chovihar);
            }
        }

        // Log query for debugging
        Log::info('SQL:', [$query->toSql()]);
        Log::info('Bindings:', [$query->getBindings()]);

        // Execute the query and get results
        $profiles = $query->with('galleries')->get();

        // Filter by income range only in PHP (optional)
        if ($preference->partner_income_range && strtolower($preference->partner_income_range) !== 'any') {
            $incomeHelper = new \App\Http\Controllers\Profile\IncomeRangeController();
            $prefIncome = $incomeHelper->getMinMax($preference->partner_income_range);

            $profiles = $profiles->filter(function ($profile) use ($incomeHelper, $prefIncome) {
                $profileIncome = $incomeHelper->getMinMax($profile->income_range);

                $overlap = $profileIncome && $prefIncome
                    ? ($profileIncome['max'] >= $prefIncome['min'] && $profileIncome['min'] <= $prefIncome['max'])
                    : false;

                Log::info('Income Range Debug', [
                    'profile_id' => $profile->id,
                    'profile_income' => $profile->income_range,
                    'profile_min' => $profileIncome['min'] ?? null,
                    'profile_max' => $profileIncome['max'] ?? null,
                    'pref_min' => $prefIncome['min'] ?? null,
                    'pref_max' => $prefIncome['max'] ?? null,
                    'overlap' => $overlap ? 'MATCH' : 'NO MATCH'
                ]);

                return $overlap;
            })->values(); // reset keys
        }

        return $profiles; // final filtered result
    }
}
