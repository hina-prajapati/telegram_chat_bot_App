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

            $existingRequest = MatchRequest::where('sender_id', $senderProfile->id)
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
