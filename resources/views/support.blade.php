<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="images/logo.jpeg" type="image/x-icon">
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="d-flex align-items-center justify-content-between card-header bg-gradient text-white"
            style="background: linear-gradient(90deg, #007bff, #00c6ff);">
            <h4 class="mb-0 text-dark"><i class="bi bi-people-fill me-2"></i>All Users Quesries</h4>
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
                            <th scope="col">User ID</th>
                            <th scope="col">USer Name</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $index => $record)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $record->chat_id }}</td>
                                <td>{{ ucfirst($record->username) }}</td>
                                <td>{{ $record->message }}</td>
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
