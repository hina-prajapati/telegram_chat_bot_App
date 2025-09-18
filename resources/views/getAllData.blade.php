<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="images/logo.jpeg" type="image/x-icon">
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="d-flex align-items-center justify-content-between card-header bg-gradient text-white"
            style="background: linear-gradient(90deg, #007bff, #00c6ff);">
            <h4 class="mb-0 text-dark"><i class="bi bi-people-fill me-2"></i>All Users</h4>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">City</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $index => $record)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                {{-- <td>
                                    <img src="{{ asset('uploads/profiles/' . ($record->profile_photo ?? 'profile_Pic.jpg')) }}"
                                         alt="Profile" width="40" height="40" class="rounded-circle shadow-sm">
                                </td> --}}
                                <td>
                                    {{-- @php
                                        $imagePath =
                                            $record->gallery && $record->gallery->image_path
                                                ? 'uploads/profiles/' . $record->gallery->image_path
                                                : 'uploads/profiles/' . ($record->profile_photo ?? 'profile_Pic.jpg');
                                    @endphp

                                    <img src="{{ asset($imagePath) }}" alt="Profile" width="40" height="40"
                                        class="rounded-circle shadow-sm"> --}}
                                    @php
                                        $galleryImage = $record->gallery->first(); // or ->last() if you prefer
                                        $imagePath = $galleryImage
                                            ? 'uploads/profiles/' . $galleryImage->image_path
                                            : 'uploads/profiles/' . ($record->profile_photo ?? 'profile_Pic.jpg');
                                    @endphp

                                    <img src="{{ asset($imagePath) }}" alt="Profile" width="40" height="40"
                                        class="rounded-circle shadow-sm">

                                </td>

                                <td class="fw-semibold">{{ $record->name }}</td>
                                <td>{{ $record->city }}</td>
                                <td>{{ ucfirst($record->gender) }}</td>
                                <td>
                                    <!-- View Button -->
                                    <a href="{{ route('profiles.show', ['id' => $record->id]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye-fill me-1"></i>View
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('profile.edit', ['id' => $record->id, 'chat_id' => $record->telegram_user_id]) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>


                                    <!-- Delete Button -->
                                    <form action="{{ route('profiles.destroy', ['id' => $record->id]) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this profile?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash-fill me-1"></i>Delete
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i class="bi bi-emoji-frown"></i> No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
