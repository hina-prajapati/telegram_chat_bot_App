<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container my-5">
    <!-- Profile Details Card -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-person-fill me-2"></i>
            <h4 class="mb-0">👤 Profile Details</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Name:</strong> {{ $record->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $record->email }}</li>
                        <li class="list-group-item"><strong>Phone:</strong> {{ $record->phone }}</li>
                        <li class="list-group-item"><strong>DOB:</strong> {{ $record->dob }}</li>
                        <li class="list-group-item"><strong>Marital Status:</strong> {{ $record->marital_status }}</li>
                        <li class="list-group-item"><strong>Gender:</strong> {{ $record->gender }}</li>
                        <li class="list-group-item"><strong>City:</strong> {{ $record->city }}</li>
                        <li class="list-group-item"><strong>State:</strong> {{ $record->state }}</li>
                        <li class="list-group-item"><strong>Religion:</strong> {{ $record->religion }}</li>
                        <li class="list-group-item"><strong>Caste:</strong> {{ $record->caste }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Education:</strong> {{ $record->education_level }} - {{ $record->education_field }}</li>
                        <li class="list-group-item"><strong>Job:</strong> {{ $record->working_sector }} - {{ $record->profession }}</li>
                        <li class="list-group-item"><strong>Income:</strong> {{ $record->income_range }}</li>
                        <li class="list-group-item"><strong>Height:</strong> {{ $record->height }} cm</li>
                        <li class="list-group-item"><strong>Body Type:</strong> {{ $record->body_type }}</li>
                        <li class="list-group-item"><strong>Skin Tone:</strong> {{ $record->skin_tone }}</li>
                        <li class="list-group-item"><strong>Diet:</strong> {{ $record->diet }}</li>
                        <li class="list-group-item"><strong>Smoking:</strong> {{ $record->smoking }}</li>
                        <li class="list-group-item"><strong>Drinking:</strong> {{ $record->drinking }}</li>
                        <li class="list-group-item"><strong>Bio:</strong> {{ $record->bio }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Preferences Card -->
    @if ($record && $record->preference)
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="bi bi-heart-fill me-2"></i>
                <h4 class="mb-0">💑 Partner Preferences</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Marital Status:</strong> {{ $record->preference->partner_marital_status ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Caste:</strong> {{ $record->preference->partner_caste ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Age Range:</strong> {{ $record->preference->partner_min_age ?? 'N/A' }} to {{ $record->preference->partner_max_age ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Height Range:</strong> {{ $record->preference->partner_min_height ?? 'N/A' }} to {{ $record->preference->partner_max_height ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Gender:</strong> {{ $record->preference->partner_gender ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Language:</strong> {{ $record->preference->partner_language ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Religion:</strong> {{ $record->preference->partner_religion ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Job Status:</strong> {{ $record->preference->partner_job_status ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Income Range:</strong> {{ $record->preference->partner_income_range ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-4">
            ⚠️ No partner preferences set for this user.
        </div>
    @endif
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
