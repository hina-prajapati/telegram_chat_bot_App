<h2>Profile Details</h2>
<ul>
    <li><strong>Name:</strong> {{ $record->name }}</li>
    <li><strong>Email:</strong> {{ $record->email }}</li>
    <li><strong>Phone:</strong> {{ $record->phone }}</li>
    <li><strong>DOB:</strong> {{ $record->dob }}</li>
    <li><strong>Marital Status:</strong> {{ $record->marital_status }}</li>
    <li><strong>Gender:</strong> {{ $record->gender }}</li>
    <li><strong>City:</strong> {{ $record->city }}</li>
    <li><strong>State:</strong> {{ $record->state }}</li>
    <li><strong>Religion:</strong> {{ $record->religion }}</li>
    <li><strong>Caste:</strong> {{ $record->caste }}</li>
    <li><strong>Education:</strong> {{ $record->education_level }} - {{ $record->education_field }}</li>
    <li><strong>Job:</strong> {{ $record->working_sector }} - {{ $record->profession }}</li>
    <li><strong>Income:</strong> {{ $record->income_range }}</li>
    <li><strong>Height:</strong> {{ $record->height }}</li>
    <li><strong>Body Type:</strong> {{ $record->body_type }}</li>
    <li><strong>Skin Tone:</strong> {{ $record->skin_tone }}</li>
    <li><strong>Diet:</strong> {{ $record->diet }}</li>
    <li><strong>Smoking:</strong> {{ $record->smoking }}</li>
    <li><strong>Drinking:</strong> {{ $record->drinking }}</li>
    <li><strong>Bio:</strong> {{ $record->bio }}</li>
</ul>

@if ($record && $record->preference)
    <h2>Partner Preferences</h2>
    <ul>
        <li><strong>Marital Status:</strong> {{ $record->preference?->partner_marital_status ?? 'N/A' }}</li>
        <li><strong>Caste:</strong> {{ $record->preference?->partner_caste ?? 'N/A' }}</li>
        <li><strong>Age Range:</strong> {{ $record->preference?->partner_min_age ?? 'N/A' }} to
            {{ $record->preference?->partner_max_age ?? 'N/A' }}</li>
        <li><strong>Height Range:</strong> {{ $record->preference?->partner_min_height ?? 'N/A' }} to
            {{ $record->preference?->partner_max_height ?? 'N/A' }}</li>
        <li><strong>Gender:</strong> {{ $record->preference?->partner_gender ?? 'N/A' }}</li>
        <li><strong>Language:</strong> {{ $record->preference?->partner_language ?? 'N/A' }}</li>
        <li><strong>Religion:</strong> {{ $record->preference?->partner_religion ?? 'N/A' }}</li>
        <li><strong>Job Status:</strong> {{ $record->preference?->partner_job_status ?? 'N/A' }}</li>
        <li><strong>Income Range:</strong> {{ $record->preference?->partner_income_range ?? 'N/A' }}</li>
    </ul>
@else
    <p>No preferences set for this user.</p>
@endif
