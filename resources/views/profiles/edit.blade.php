<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Make sure viewport is set -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        button[type="submit"] {
            /* width: 100%; */
            padding: 12px;
            background-color: #2563eb;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .sm-btn {
            margin-top: 20px;
        }

        button.btn.sm-btn {
            width: 200px;
        }

        button[type="submit"]:hover {
            background-color: #1d4ed8;
        }

        .form-control {
            height: 40px;
        }
    </style>
</head>

<body>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container py-3">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <h2>Edit Your Profile</h2>
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" name="name" class="form-control" value="{{ $profile->name }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="dob">Date of Birth:</label>
                                <input type="date" name="dob" class="form-control" value="{{ $profile->dob }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="bio">Bio:</label>
                                <input type="text" name="bio" class="form-control" value="{{ $profile->bio }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" name="email" class="form-control" value="{{ $profile->email }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Gender:</label>
                                <select name="gender" class="form-control">
                                    @php
                                        $genders = [
                                            'Male' => 'Male',
                                            'Female' => 'Female',
                                        ];

                                    @endphp

                                    @foreach ($genders as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('gender', $profile->gender ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Marital Status:</label>
                                <select name="marital_status" class="form-control">
                                    <option value="Single"
                                        {{ old('marital_status', $profile->marital_status) === 'Single' ? 'selected' : '' }}>
                                        Single
                                    </option>
                                    <option value="Divorced"
                                        {{ old('marital_status', $profile->marital_status) === 'Divorced' ? 'selected' : '' }}>
                                        Divorced</option>
                                    <option value="Widowed"
                                        {{ old('marital_status', $profile->marital_status) === 'Widowed' ? 'selected' : '' }}>
                                        Widowed</option>
                                    <option value="Any"
                                        {{ old('marital_status', $profile->marital_status) === 'Any' ? 'selected' : '' }}>
                                        Any
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">State:</label>
                                <select id="state" name="state" class="form-control">
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ old('state', $profile->state) == $state->name ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">City:</label>
                                <select id="city" name="city" class="form-control">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city', $profile->city) == $city->name ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Mother Tongue:</label>
                                <select name="mother_tongue" class="form-control">
                                    @php
                                        $tongues = [
                                            'Hindi' => 'Hindi',
                                            'Bengali' => 'Bengali',
                                            'Marathi' => 'Marathi',
                                            'Telugu' => 'Telugu',
                                            'Tamil' => 'Tamil',
                                            'Gujarati' => 'Gujarati',
                                            'Urdu' => 'Urdu',
                                            'Kannada' => 'Kannada',
                                            'Odia/Oriya' => 'Odia/Oriya',
                                            'Malayalam' => 'Malayalam',
                                            'Punjabi' => 'Punjabi',
                                            'Assamese' => 'Assamese',
                                            'Maithili' => 'Maithili',
                                            'Konkani' => 'Konkani',
                                            'Dogri' => 'Dogri',
                                            'Kashmiri' => 'Kashmiri',
                                            'Manipuri' => 'Manipuri',
                                            'Nepali' => 'Nepali',
                                            'Bodo' => 'Bodo',
                                            'Santali' => 'Santali',
                                            'Sanskrit' => 'Sanskrit',
                                            'Sindhi' => 'Sindhi',
                                            'Tulu' => 'Tulu',
                                            'Bhojpuri' => 'Bhojpuri',
                                            'Haryanvi' => 'Haryanvi',
                                            'Kutchhi' => 'Kutchhi',
                                            'Marwari' => 'Marwari',
                                            'English' => 'English',
                                            'Other' => 'Other',
                                        ];

                                    @endphp

                                    @foreach ($tongues as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('mother_tongue', $profile->mother_tongue ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Religion:</label>
                                <select name="religion" class="form-control">
                                    @php
                                        $religions = [
                                            'Hinduism' => 'Hinduism',
                                            'Islam' => 'Islam',
                                            'Christianity' => 'Christianity',
                                            'Sikhism' => 'Sikhism',
                                            'Buddhism' => 'Buddhism',
                                            'Jainism' => 'Jainism',
                                            'Parsi (Zoroastrian)' => 'Parsi (Zoroastrian)',
                                            'Judaism' => 'Judaism',
                                            'Tribal / Indigenous' => 'Tribal / Indigenous',
                                            'No Religion / Atheist' => 'No Religion / Atheist',
                                            'Other' => 'Other',
                                        ];

                                    @endphp

                                    @foreach ($religions as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('religion', $profile->religion ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- caste --}}

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="caste">Caste:</label>
                                <select id="caste" name="caste" class="form-control">
                                    @foreach ($casts as $cast)
                                        <option value="{{ $cast->caste_id }}"
                                            {{ old('caste', $profile->caste ?? '') == $cast->caste_name ? 'selected' : '' }}>
                                            {{ $cast->caste_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="subCaste">Sub Caste:</label>
                                <select id="subCaste" name="sub_caste" class="form-control">
                                    @foreach ($subcasts as $subcast)
                                        <option value="{{ $subcast->sub_caste_id }}"
                                            {{ old('sub_caste', $profile->sub_caste ?? '') == $subcast->sub_caste_name ? 'selected' : '' }}>
                                            {{ $subcast->sub_caste_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Education Level:</label>
                                <select name="education_level" class="form-control">
                                    @php
                                        $educationLevels = [
                                            'High School (or equivalent like 10th Pass)' =>
                                                'High School (or equivalent like 10th Pass)',
                                            'Intermediate/Diploma (12th Pass, ITI, etc.)' =>
                                                'Intermediate/Diploma (12th Pass, ITI, etc.)',
                                            "Bachelor's Degree" => "Bachelor's Degree",
                                            "Master's Degree" => "Master's Degree",
                                            'Doctorate/Ph.D.' => 'Doctorate/Ph.D.',
                                            'Post Doctorate' => 'Post Doctorate',
                                            'Professional Degree (e.g., CA, CS, MBBS, etc.)' =>
                                                'Professional Degree (e.g., CA, CS, MBBS, etc.)',
                                            'Other' => 'Other',
                                            'Did not complete high school' => 'Did not complete high school',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($educationLevels as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('education_level', $profile->education_level ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Education Field:</label>
                                <select name="education_field" class="form-control">
                                    @php
                                        $educationFields = [
                                            'Arts/Humanities' => 'Arts/Humanities',
                                            'Science' => 'Science',
                                            'Commerce' => 'Commerce',
                                            'Engineering' => 'Engineering',
                                            'Medical' => 'Medical',
                                            'Law' => 'Law',
                                            'Management (MBA, BBA, etc.)' => 'Management (MBA, BBA, etc.)',
                                            'Information Technology (IT)' => 'Information Technology (IT)',
                                            'Architecture' => 'Architecture',
                                            'Pharmacy' => 'Pharmacy',
                                            'Agriculture' => 'Agriculture',
                                            'Media/Journalism' => 'Media/Journalism',
                                            'Fine Arts/Performing Arts' => 'Fine Arts/Performing Arts',
                                            'Education' => 'Education',
                                            'Vocational' => 'Vocational',
                                            'Designing' => 'Designing',
                                            'Sports' => 'Sports',
                                            'Other' => 'Other',
                                        ];

                                    @endphp

                                    @foreach ($educationFields as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('education_field', $profile->education_field ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Job Status:</label>
                                <select name="job_status" class="form-control">
                                    @php
                                        $jobStatuses = [
                                            'Employed' => 'Employed',
                                            'Self-Employed / Business' => 'Self-Employed / Business',
                                            'Homemaker/Housewife' => 'Homemaker/Housewife',
                                            'Not Working' => 'Not Working',
                                            'Student' => 'Student',
                                            'Retired' => 'Retired',
                                            'Unemployed / Looking for a Job' => 'Unemployed / Looking for a Job',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($jobStatuses as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('job_status', $profile->job_status ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Working Sector:</label>
                                <select name="working_sector" class="form-control">
                                    @php
                                        $workingSectors = [
                                            'Government/Public Sector' => 'Government/Public Sector',
                                            'Private Sector' => 'Private Sector',
                                            'Self-Employed / Business' => 'Self-Employed / Business',
                                            'NGO/Social Services' => 'NGO/Social Services',
                                            'Defense/Civil Services' =>
                                                'Defense/Civil Services (e.g., IAS, IPS, Army, Navy, etc.)',
                                            'Education/Academia' => 'Education/Academia',
                                            'Healthcare' => 'Healthcare',
                                            'Information Technology (IT)' => 'Information Technology (IT)',
                                            'Banking/Finance' => 'Banking/Finance',
                                            'Media/Entertainment' => 'Media/Entertainment',
                                            'Hospitality' => 'Hospitality',
                                            'Retail/FMCG' => 'Retail/FMCG',
                                            'Manufacturing' => 'Manufacturing',
                                            'Agriculture/Farming' => 'Agriculture/Farming',
                                            'Real Estate/Construction' => 'Real Estate/Construction',
                                            'Legal' => 'Legal',
                                            'Creative Arts' => 'Creative Arts',
                                            'Other' => 'Other',
                                        ];

                                    @endphp

                                    @foreach ($workingSectors as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('working_sector', $profile->working_sector ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- profession --}}

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Profession:</label>
                                <select name="profession" class="form-control" id="profession">
                                    @foreach ($profession_categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('profession', $profile->profession ?? '') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Specific Profession:</label>
                                <select name="specific_profession" class="form-control" id="specificProfession">
                                    @foreach ($specificProfession as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('specific_profession', $profile->specific_profession ?? '') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Phone:</label>
                                <input type="number" name="phone" class="form-control"
                                    value="{{ $profile->phone }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="images">Upload Profile Images:</label>
                                <input type="file" name="images[]" class="form-control" multiple>

                                @if ($profile->galleries->isNotEmpty())
                                    @foreach ($profile->galleries as $gallery)
                                        <img src="{{ asset('uploads/profiles/' . $gallery->image_path) }}"
                                            width="50" alt="Profile Image">
                                        <input type="checkbox" name="delete_images[]"
                                            value="{{ $gallery->id }}">Delete
                                    @endforeach
                                @else
                                    <p>No image uploaded</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="income_range">Income Range</label>
                                <select name="income_range" id="income_range" class="form-control" required>
                                    <option value="">-- Select Income Range --</option>
                                    <option value="Any"
                                        {{ old('income_range', $profile->income_range ?? '') == 'Any' ? 'selected' : '' }}>
                                        Any
                                    </option>
                                    <option value="₹0 - ₹1L"
                                        {{ old('income_range', $profile->income_range ?? '') == '₹0 - ₹1L' ? 'selected' : '' }}>
                                        ₹0
                                        - ₹1L</option>
                                    <option value="₹1L - ₹2L"
                                        {{ old('income_range', $profile->income_range ?? '') == '₹1L - ₹2L' ? 'selected' : '' }}>
                                        ₹1L - ₹2L</option>
                                    <option value="₹2L - ₹5L"
                                        {{ old('income_range', $profile->income_range ?? '') == '₹2L - ₹5L' ? 'selected' : '' }}>
                                        ₹2L - ₹5L</option>
                                    <option value="₹5L - ₹10L"
                                        {{ old('income_range', $profile->income_range ?? '') == '₹5L - ₹10L' ? 'selected' : '' }}>
                                        ₹5L - ₹10L</option>
                                    <option value="₹10L - ₹25L"
                                        {{ old('income_range', $profile->income_range ?? '') == '₹10L - ₹25L' ? 'selected' : '' }}>
                                        ₹10L - ₹25L</option>
                                    <option value="Above ₹25L"
                                        {{ old('income_range', $profile->income_range ?? '') == 'Above ₹25L' ? 'selected' : '' }}>
                                        Above ₹25L</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Diet:</label>
                                <select name="diet" class="form-control">
                                    @php
                                        $diets = [
                                            'Veg' => 'Veg',
                                            'Non-Veg' => 'Non-Veg',
                                            'Jain' => 'Jain',
                                            'Any' => 'Any',
                                        ];

                                    @endphp

                                    @foreach ($diets as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('diet', $profile->diet ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" id="choviharWrap" style="display: none;">
                            <div class="form-group">
                                <label for="name">Partner Chovihar:</label>
                                <select name="chovihar" class="form-control">
                                    @php
                                        $choviharOptions = [
                                            'Yes' => 'Yes',
                                            'No' => 'No',
                                        ];
                                    @endphp
                                    @foreach ($choviharOptions as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('partner_chovihar', $profile->preference->partner_chovihar ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Smoking:</label>
                                <select name="smoking" class="form-control">
                                    @php
                                        $smokings = [
                                            'Yes' => 'Yes',
                                            'No' => 'No',
                                            'Occasionally' => 'Occasionally',
                                        ];

                                    @endphp

                                    @foreach ($smokings as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('smoking', $profile->smoking ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Drinking:</label>
                                <select name="drinking" class="form-control">
                                    @php
                                        $drinkings = [
                                            'Yes' => 'Yes',
                                            'No' => 'No',
                                            'Occasionally' => 'Occasionally',
                                        ];

                                    @endphp

                                    @foreach ($drinkings as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('drinking', $profile->drinking ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="height">Select Height</label>
                                <select name="height" class="form-control" required>
                                    <option value="">-- Select Height --</option>
                                    @for ($ft = 4; $ft <= 6; $ft++)
                                        @for ($in = 0; $in <= 11; $in++)
                                            @php
                                                $cm = round($ft * 30.48 + $in * 2.54);
                                                $label = "{$ft} ft {$in} in → {$cm} cm";
                                            @endphp
                                            <option value="{{ $cm }}"
                                                {{ old('height', $profile->height) == $cm ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Body Type:</label>
                                <select name="body_type" class="form-control">
                                    @php
                                        $bodyTypes = [
                                            'Slim' => 'Slim',
                                            'Athletic / Fit' => 'Athletic / Fit',
                                            'Average' => 'Average',
                                            'Heavy / Large' => 'Heavy / Large',
                                            'Well-Built' => 'Well-Built',
                                            'Curvy' => 'Curvy',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($bodyTypes as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('body_type', $profile->body_type ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Skin Tone:</label>
                                <select name="skin_tone" class="form-control">
                                    @php
                                        $skinTones = [
                                            'Fair' => 'Fair',
                                            'Wheatish' => 'Wheatish',
                                            'Wheatish Brown' => 'Wheatish Brown',
                                            'Dark' => 'Dark',
                                            'Olive' => 'Olive',
                                            'Brown' => 'Brown',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($skinTones as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('skin_tone', $profile->skin_tone ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <h2 class="text-center">Partner Preference:</h2>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Marital Status:</label>
                                <select name="partner_marital_status" class="form-control">
                                    @php
                                        $partnerMaritalStatus = [
                                            'Single' => 'Single',
                                            'Married' => 'Married',
                                            'Divorced' => 'Divorced',
                                            'Any' => 'Any',
                                        ];

                                    @endphp

                                    @foreach ($partnerMaritalStatus as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('partner_marital_status', $profile->preference->partner_marital_status ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Caste:</label>
                                <select id="partnerCaste" name="partner_caste" class="form-control">
                                    @foreach ($partnerCast as $cast)
                                        @if (strtolower($cast->caste_name) !== 'other')
                                            <option value="{{ $cast->caste_name }}"
                                                {{ old('partner_caste', $profile->preference->partner_caste ?? '') == $cast->caste_name ? 'selected' : '' }}>
                                                {{ $cast->caste_name }}
                                            </option>
                                        @endif
                                    @endforeach

                                    <option value="Any"
                                        {{ strtolower(old('partner_caste', $profile->preference->partner_caste ?? '')) == 'any' ? 'selected' : '' }}>
                                        Any
                                    </option>
                                </select>

                            </div>
                        </div>

                        {{-- partnerCast --}}

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Diet:</label>
                                <select name="partner_diet" class="form-control">
                                    @php
                                        $diets = [
                                            'Veg' => 'Veg',
                                            'Non-Veg' => 'Non-Veg',
                                            'Jain' => 'Jain',
                                            'Any' => 'Any',
                                        ];
                                    @endphp

                                    @foreach ($diets as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('partner_diet', $profile->preference->partner_diet ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" id="choviharWrapper" style="display: none;">
                            <div class="form-group">
                                <label for="name">Partner Chovihar:</label>
                                <select name="partner_chovihar" class="form-control">
                                    @php
                                        $choviharOptions = [
                                            'Yes' => 'Yes',
                                            'No' => 'No',
                                        ];
                                    @endphp
                                    @foreach ($choviharOptions as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('partner_chovihar', $profile->preference->partner_chovihar ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Partner Profession --}}


                        <!-- Partner Profession Dropdown -->
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="partnerprofession">Partner Profession:</label>
                                <select id="partnerprofession" name="partner_profession" class="form-control">
                                    @foreach ($partnerProfession_categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('partner_profession', $preference->partner_profession ?? '') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Partner Specific Profession Dropdown -->
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="partnerspecificProfession">Partner Specific Profession:</label>
                                <select id="partnerspecificProfession" name="partner_specific_profession"
                                    class="form-control">
                                    @foreach ($partnerSpecificProfession as $specific)
                                        <option value="{{ $specific->id }}"
                                            {{ old('partner_specific_profession', $preference->partner_specific_profession ?? '') === $specific->name ? 'selected' : '' }}>
                                            {{ $specific->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Education Level:</label>
                                <select name="partner_education_level" class="form-control">
                                    @php
                                        $educationLevels = [
                                            'High School (or equivalent like 10th Pass)' =>
                                                'High School (or equivalent like 10th Pass)',
                                            'Intermediate/Diploma (12th Pass, ITI, etc.)' =>
                                                'Intermediate/Diploma (12th Pass, ITI, etc.)',
                                            "Bachelor's Degree" => "Bachelor's Degree",
                                            "Master's Degree" => "Master's Degree",
                                            'Doctorate/Ph.D.' => 'Doctorate/Ph.D.',
                                            'Post Doctorate' => 'Post Doctorate',
                                            'Professional Degree (e.g., CA, CS, MBBS, etc.)' =>
                                                'Professional Degree (e.g., CA, CS, MBBS, etc.)',
                                            'Any' => 'Any',
                                            'Did not complete high school' => 'Did not complete high school',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($educationLevels as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('partner_education_level', $profile->preference->partner_education_level ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Min Age:</label>
                                <input type="number" class="form-control" name="partner_min_age"
                                    value="{{ $profile->preference->partner_min_age }}" >
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Max Age:</label>
                                <input type="number" class="form-control" name="partner_max_age"
                                    value="{{ $profile->preference->partner_max_age }}" >
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="height">Parnter Min Height</label>
                                <select name="partner_min_height" class="form-control" required>
                                    <option value="">-- Select Height --</option>
                                    @for ($ft = 4; $ft <= 6; $ft++)
                                        @for ($in = 0; $in <= 11; $in++)
                                            @php
                                                $cm = round($ft * 30.48 + $in * 2.54);
                                                $label = "{$ft} ft {$in} in → {$cm} cm";
                                            @endphp
                                            <option value="{{ $cm }}"
                                                {{ old('partner_min_height', $profile->preference->partner_min_height) == $cm ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="height">Parnter Max Height</label>
                                <select name="partner_max_height" class="form-control" required>
                                    <option value="">-- Select Height --</option>
                                    @for ($ft = 4; $ft <= 6; $ft++)
                                        @for ($in = 0; $in <= 11; $in++)
                                            @php
                                                $cm = round($ft * 30.48 + $in * 2.54);
                                                $label = "{$ft} ft {$in} in → {$cm} cm";
                                            @endphp
                                            <option value="{{ $cm }}"
                                                {{ old('partner_max_height', $profile->preference->partner_max_height) == $cm ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Religion:</label>
                                <select name="partner_religion" class="form-control">
                                    @php
                                        $partnerReligions = [
                                            'Hinduism' => 'Hinduism',
                                            'Islam' => 'Islam',
                                            'Christianity' => 'Christianity',
                                            'Sikhism' => 'Sikhism',
                                            'Buddhism' => 'Buddhism',
                                            'Jainism' => 'Jainism',
                                            'Parsi (Zoroastrian)' => 'Parsi (Zoroastrian)',
                                            'Judaism' => 'Judaism',
                                            'Tribal / Indigenous' => 'Tribal / Indigenous',
                                            'No Religion / Atheist' => 'No Religion / Atheist',
                                            'Any' => 'Any',
                                        ];

                                    @endphp

                                    @foreach ($partnerReligions as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('partner_religion', $profile->preference->partner_religion ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Job Status:</label>
                                <select name="partner_job_status" class="form-control">
                                    @php
                                        $partnerJobStatuses = [
                                            'Employed' => 'Employed',
                                            'Self-Employed / Business' => 'Self-Employed / Business',
                                            'Homemaker/Housewife' => 'Homemaker/Housewife',
                                            'Not Working' => 'Not Working',
                                            'Student' => 'Student',
                                            'Retired' => 'Retired',
                                            'Unemployed / Looking for a Job' => 'Unemployed / Looking for a Job',
                                            'Prefer not to say' => 'Prefer not to say',
                                        ];

                                    @endphp

                                    @foreach ($partnerJobStatuses as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('partner_job_status', $profile->preference->partner_job_status ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Partner Mother Tongue:</label>
                                <select name="partner_language" class="form-control">
                                    @php
                                        $partnertongues = [
                                            'Hindi' => 'Hindi',
                                            'Bengali' => 'Bengali',
                                            'Marathi' => 'Marathi',
                                            'Telugu' => 'Telugu',
                                            'Tamil' => 'Tamil',
                                            'Gujarati' => 'Gujarati',
                                            'Urdu' => 'Urdu',
                                            'Kannada' => 'Kannada',
                                            'Odia/Oriya' => 'Odia/Oriya',
                                            'Malayalam' => 'Malayalam',
                                            'Punjabi' => 'Punjabi',
                                            'Assamese' => 'Assamese',
                                            'Maithili' => 'Maithili',
                                            'Konkani' => 'Konkani',
                                            'Dogri' => 'Dogri',
                                            'Kashmiri' => 'Kashmiri',
                                            'Manipuri' => 'Manipuri',
                                            'Nepali' => 'Nepali',
                                            'Bodo' => 'Bodo',
                                            'Santali' => 'Santali',
                                            'Sanskrit' => 'Sanskrit',
                                            'Sindhi' => 'Sindhi',
                                            'Tulu' => 'Tulu',
                                            'Bhojpuri' => 'Bhojpuri',
                                            'Haryanvi' => 'Haryanvi',
                                            'Kutchhi' => 'Kutchhi',
                                            'Marwari' => 'Marwari',
                                            'English' => 'English',
                                            'Any' => 'Any',
                                        ];
                                    @endphp

                                    @foreach ($partnertongues as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('partner_language', $profile->preference->partner_language ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="income_range">Partner Income Range</label>
                                <select name="partner_income_range" id="partner_income_range" class="form-control"
                                    required>
                                    <option value="">-- Select Income Range --</option>
                                    <option value="Any"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == 'Any' ? 'selected' : '' }}>
                                        Any
                                    </option>
                                    <option value="₹0 - ₹1L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == '₹0 - ₹1L' ? 'selected' : '' }}>
                                        ₹0
                                        - ₹1L</option>
                                    <option value="₹1L - ₹2L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == '₹1L - ₹2L' ? 'selected' : '' }}>
                                        ₹1L - ₹2L</option>
                                    <option value="₹2L - ₹5L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == '₹2L - ₹5L' ? 'selected' : '' }}>
                                        ₹2L - ₹5L</option>
                                    <option value="₹5L - ₹10L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == '₹5L - ₹10L' ? 'selected' : '' }}>
                                        ₹5L - ₹10L</option>
                                    <option value="₹10L - ₹25L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == '₹10L - ₹25L' ? 'selected' : '' }}>
                                        ₹10L - ₹25L</option>
                                    <option value="Above ₹25L"
                                        {{ old('partner_income_range', $profile->preference->partner_income_range ?? '') == 'Above ₹25L' ? 'selected' : '' }}>
                                        Above ₹25L</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn sm-btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> --}}
    <script>
        document.getElementById('state').addEventListener('change', function() {
            let stateId = this.value;
            let selectedCityId = '{{ old('city', $profile->city) }}'; // Preserve selected

            fetch(`/get-cities/${stateId}`)
                .then(response => response.json())
                .then(data => {
                    let citySelect = document.getElementById('city');
                    citySelect.innerHTML = '';

                    data.forEach(city => {
                        let option = document.createElement('option');
                        option.value = city.id;
                        option.text = city.name;

                        // Preserve selected city
                        if (city.id == selectedCityId) {
                            option.selected = true;
                        }

                        citySelect.appendChild(option);
                    });
                });
        });

        document.getElementById('caste').addEventListener('change', function() {
            let casteId = this.value;
            console.log(casteId)

            fetch(`/get-subcast/${casteId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    let subCastSelect = document.getElementById('subCaste');
                    subCastSelect.innerHTML = '';

                    data.forEach(subcaste => {
                        let option = document.createElement('option');
                        option.value = subcaste.sub_caste_id;
                        option.text = subcaste.sub_caste_name;
                        subCastSelect.appendChild(option);
                    });
                });
        });

        document.getElementById('profession').addEventListener('change', function() {
            const professionId = this.value;
            console.log(profession)

            fetch(`/get-specific-professions/${professionId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    let sepcificSelect = document.getElementById('specificProfession');
                    sepcificSelect.innerHTML = '';

                    data.forEach(profession => {
                        let option = document.createElement('option');
                        option.value = profession.id;
                        option.text = profession.name;
                        sepcificSelect.appendChild(option);
                    });
                });
        });
    </script>
    <script>
        function togglePartnerChovihar() {
            const dietSelect = document.querySelector('select[name="partner_diet"]');
            const choviharWrapper = document.getElementById('choviharWrapper');

            if (dietSelect && choviharWrapper) {
                if (dietSelect.value === 'Jain') {
                    choviharWrapper.style.display = 'block';
                } else {
                    choviharWrapper.style.display = 'none';
                }
            }
        }

        function toggleUserChovihar() {
            const dietSelect = document.querySelector('select[name="diet"]');
            const choviharWrapper = document.getElementById('choviharWrap');

            if (dietSelect && choviharWrapper) {
                if (dietSelect.value === 'Jain') {
                    choviharWrapper.style.display = 'block';
                } else {
                    choviharWrapper.style.display = 'none';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            togglePartnerChovihar();
            toggleUserChovihar();

            const partnerDietSelect = document.querySelector('select[name="partner_diet"]');
            if (partnerDietSelect) {
                partnerDietSelect.addEventListener('change', togglePartnerChovihar);
            }

            const userDietSelect = document.querySelector('select[name="diet"]');
            if (userDietSelect) {
                userDietSelect.addEventListener('change', toggleUserChovihar);
            }
        });
    </script>


    <script>
        document.getElementById('partnerprofession').addEventListener('change', function() {
            const professionId = this.value;

            fetch(`/get-partner-specific-professions/${professionId}`)
                .then(response => response.json())
                .then(data => {
                    const specificDropdown = document.getElementById('partnerspecificProfession');
                    specificDropdown.innerHTML = '';

                    data.forEach(profession => {
                        const option = document.createElement('option');
                        option.value = profession.id;
                        option.text = profession.name;
                        specificDropdown.appendChild(option);
                    });
                });
        });
    </script>

</body>

</html>
