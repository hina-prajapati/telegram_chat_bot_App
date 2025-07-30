<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } */

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 100%;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #111827;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #374151;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #1d4ed8;
        }

        .form-control {
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Your Profile</h2>
        <form action="{{ route('profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="profile_id" value="{{ $profile->id }}">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="{{ $profile->name }}" required>
                </div>
                <div class="col-md-3">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" value="{{ $profile->dob }}" required>
                </div>

                <div class="col-md-3">
                    <label for="bio">Bio:</label>
                    <input type="text" name="bio" value="{{ $profile->bio }}" required>
                </div>

                <div class="col-md-3">
                    <label for="email">Email:</label>
                    <input type="text" name="email" value="{{ $profile->email }}" required>
                </div>

                <div class="col-md-3">
                    <label for="name">Marital Status:</label>
                    <select name="marital_status" class="form-control">
                        <option value="Single"
                            {{ old('marital_status', $profile->marital_status) === 'Single' ? 'selected' : '' }}>Single
                        </option>
                        <option value="Divorced"
                            {{ old('marital_status', $profile->marital_status) === 'Divorced' ? 'selected' : '' }}>
                            Divorced</option>
                        <option value="Widowed"
                            {{ old('marital_status', $profile->marital_status) === 'Widowed' ? 'selected' : '' }}>
                            Widowed</option>
                        <option value="Any"
                            {{ old('marital_status', $profile->marital_status) === 'Any' ? 'selected' : '' }}>Any
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="name">State:</label>
                    <select name="state" class="form-control">
                        <option value="">Select State</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->name }}"
                                {{ old('state', $profile->state) == $state->name ? 'selected' : '' }}>
                                {{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="name">City:</label>
                    <select name="city" class="form-control">
                        <option value="">Select City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->name }}"
                                {{ old('city', $profile->city) == $city->name ? 'selected' : '' }}>{{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="name">Mother Tongue:</label>
                    <select name="mother_tongue" class="form-control">
                        @php
                            $tongues = [
                                'Hindi' => 'Hindi',
                                'Marathi' => 'Marathi',
                                'Gujarati' => 'Gujarati',
                                'Punjabi' => 'Punjabi',
                                'Tamil' => 'Tamil',
                                'Telugu' => 'Telugu',
                                'Bengali' => 'Bengali',
                                'Urdu' => 'Urdu',
                                'Kannada' => 'Kannada',
                                'Malayalam' => 'Malayalam',
                                'Odia' => 'Odia',
                                'Assamese' => 'Assamese',
                                'Nepali' => 'Nepali',
                                'Sindhi' => 'Sindhi',
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

                <div class="col-md-3">
                    <label for="name">Religion:</label>
                    <select name="religion" class="form-control">
                        @php
                            $religions = [
                                'Hindu' => 'Hindu',
                                'Muslim' => 'Muslim',
                                'Christian' => 'Christian',
                                'Sikh' => 'Sikh',
                                'Buddhist' => 'Buddhist',
                                'Jain' => 'Jain',
                                'Parsi (Zoroastrian)' => 'Parsi (Zoroastrian)',
                                'Jewish' => 'Jewish',
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

                <div class="col-md-3">
                    <label for="name">Caste:</label>
                    <select name="caste" class="form-control">
                        @php
                            $castes = [
                                'Brahmin' => 'Brahmin',
                                'Kshatriya' => 'Kshatriya',
                                'Vaishya' => 'Vaishya',
                                'Shudra' => 'Shudra',
                                'SC' => 'SC',
                                'ST' => 'ST',
                                'OBC' => 'OBC',
                                'Jain' => 'Jain',
                                'Sindhi' => 'Sindhi',
                                'Rajput' => 'Rajput',
                                'Yadav' => 'Yadav',
                                'Kayastha' => 'Kayastha',
                                'Maratha' => 'Maratha',
                                'Agarwal' => 'Agarwal',
                                'Koli' => 'Koli',
                                'Kumhar' => 'Kumhar',
                                'Patel' => 'Patel',
                                'Reddy' => 'Reddy',
                                'Kapoor' => 'Kapoor',
                                'Gupta' => 'Gupta',
                                'Bania' => 'Bania',
                                'Kurmi' => 'Kurmi',
                                'Maurya' => 'Maurya',
                                'Chaudhary' => 'Chaudhary',
                                'Jat' => 'Jat',
                                'Lodha' => 'Lodha',
                                'Saini' => 'Saini',
                                'Teli' => 'Teli',
                                'Nair' => 'Nair',
                                'Menon' => 'Menon',
                                'Pillai' => 'Pillai',
                                'Chettiar' => 'Chettiar',
                                'Mudaliar' => 'Mudaliar',
                                'Gounder' => 'Gounder',
                                'Nadar' => 'Nadar',
                                'Ezhava' => 'Ezhava',
                                'Naidu' => 'Naidu',
                                'Nayak' => 'Nayak',
                                'Gujar' => 'Gujar',
                                'Ahir' => 'Ahir',
                                'Meena' => 'Meena',
                                'Meitei' => 'Meitei',
                                'Chamar' => 'Chamar',
                                'Dhangar' => 'Dhangar',
                                'Giri' => 'Giri',
                                'Prajapati' => 'Prajapati',
                                'Mali' => 'Mali',
                                'Bhoi' => 'Bhoi',
                                'Bhandari' => 'Bhandari',
                                'Sonar' => 'Sonar',
                                'Dhobi' => 'Dhobi',
                                'Khatik' => 'Khatik',
                                'Barber (Nai)' => 'Barber (Nai)',
                                'Kahar' => 'Kahar',
                                'Tonk Kshatriya' => 'Tonk Kshatriya',
                                'Bairwa' => 'Bairwa',
                                'Paswan' => 'Paswan',
                                'Pal' => 'Pal',
                                'Rawat' => 'Rawat',
                                'Thakur' => 'Thakur',
                                'Lingayat' => 'Lingayat',
                                'Devanga' => 'Devanga',
                                'Kamma' => 'Kamma',
                                'Vokkaliga' => 'Vokkaliga',
                                'Balija' => 'Balija',
                                'Kapu' => 'Kapu',
                                'Jatav' => 'Jatav',
                                'Mochi' => 'Mochi',
                                'Valmiki' => 'Valmiki',
                                'Bhatt' => 'Bhatt',
                                'Bhils' => 'Bhils',
                                'Gond' => 'Gond',
                                'Halba' => 'Halba',
                                'Kunbi' => 'Kunbi',
                                'Maheshwari' => 'Maheshwari',
                                'Modi' => 'Modi',
                                'Oswal' => 'Oswal',
                                'Chandravanshi' => 'Chandravanshi',
                                'Rajgond' => 'Rajgond',
                                'Malviya' => 'Malviya',
                                'Dixit' => 'Dixit',
                                'Trivedi' => 'Trivedi',
                                'Chaturvedi' => 'Chaturvedi',
                                'Tripathi' => 'Tripathi',
                                'Mishra' => 'Mishra',
                                'Sharma' => 'Sharma',
                                'Pandey' => 'Pandey',
                                'Tiwari' => 'Tiwari',
                                'Joshi' => 'Joshi',
                                'Pathak' => 'Pathak',
                                'Dwivedi' => 'Dwivedi',
                                'Upadhyay' => 'Upadhyay',
                                'Bhargava' => 'Bhargava',
                                'Dubey' => 'Dubey',
                                'Bajpai' => 'Bajpai',
                                'Bhatnagar' => 'Bhatnagar',
                                'Nigam' => 'Nigam',
                                'Srivastava' => 'Srivastava',
                                'Verma' => 'Verma',
                                'Other' => 'Other',
                            ];

                        @endphp

                        @foreach ($castes as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('caste', $profile->caste ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-3">
                    <label for="name">Education Level:</label>
                    <select name="education_level" class="form-control">
                        @php
                            $educationLevels = [
                                'High School' => 'High School',
                                'Diploma' => 'Diploma',
                                "Bachelor's" => "Bachelor's",
                                "Master's" => "Master's",
                                'PhD' => 'PhD',
                                'Other' => 'Other',
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

                <div class="col-md-3">
                    <label for="name">Education Field:</label>
                    <select name="education_field" class="form-control">
                        @php
                            $educationFields = [
                                'Engineering' => 'Engineering',
                                'Arts' => 'Arts',
                                'Commerce' => 'Commerce',
                                'Science' => 'Science',
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

                <div class="col-md-3">
                    <label for="name">Job Status:</label>
                    <select name="job_status" class="form-control">
                        @php
                            $jobStatuses = [
                                'Employed' => 'Employed',
                                'Self-employed' => 'Self-employed',
                                'Student' => 'Student',
                                'Unemployed' => 'Unemployed',
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

                <div class="col-md-3">
                    <label for="name">Working Sector:</label>
                    <select name="working_sector" class="form-control">
                        @php
                            $workingSectors = [
                                'Private' => 'Private',
                                'Government' => 'Government',
                                'Business' => 'Business',
                                'Freelance' => 'Freelance',
                                'Student' => 'Student',
                                'Not Working' => 'Not Working',
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

                <div class="col-md-3">
                    <label for="name">Profession:</label>
                    <select name="profession" class="form-control">
                        @php
                            $professions = [
                                'Software Engineer' => 'Software Engineer',
                                'Doctor' => 'Doctor',
                                'Teacher' => 'Teacher',
                                'Businessman' => 'Businessman',
                                'Student' => 'Student',
                                'House Maker' => 'House Maker',
                                'Other' => 'Other',
                            ];

                        @endphp

                        @foreach ($professions as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('profession', $profile->profession ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="name">Phone:</label>
                    <input type="number" name="phone" value="{{ $profile->phone }}" required>
                </div>

                <div class="col-md-3">
                    <label for="images">Upload Profile Images:</label>
                    <input type="file" name="images[]" multiple required>

                    @if ($profile->galleries->isNotEmpty())
                        @foreach ($profile->galleries as $gallery)
                            <img src="{{ asset('uploads/profiles/' . $gallery->image_path) }}" width="150"
                                alt="Profile Image">
                        @endforeach
                    @else
                        <p>No image uploaded</p>
                    @endif
                </div>


            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</body>

</html>
