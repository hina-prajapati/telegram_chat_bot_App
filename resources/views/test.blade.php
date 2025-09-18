<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Make sure viewport is set -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional: Add padding and adjust input spacing */
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        label {
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .sm-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="form-container">
                    <h3 class="mb-4">Edit Your Profile</h3>

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="profile_id">

                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label for="dob">Date of Birth:</label>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label for="bio">Bio:</label>
                                    <input type="text" name="bio" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="text" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary sm-btn">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
