@php
    $fullName = Auth::user()->username ? Auth::user()->username : old('username');
    $country = Auth::user()->country ? Auth::user()->country : old('country');
@endphp
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    @include('flash-message')
    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form class="row g-3" method="POST" action="{{ route('admin.profile.update') }}" id="profileForm" enctype='multipart/form-data'>
                                    {!! csrf_field() !!}
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <label for="profile_pic">
                                                <img id="profile-pic-preview" src="{{ Auth::user()->avatar_url }}" alt="Profile">
                                                <div class="pt-2">
                                                    <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image" id="upload-button">
                                                        <i class="bi bi-upload"></i>
                                                    </a>
                                                    <input type="file" id="profile_pic" name="avatar_url" style="display: none;" accept="image/*"/>
                                                    <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image" id="remove-button">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name:</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="full_name" type="text" class="form-control" id="full_name" value="{{ $fullName }}" required>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}"/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="user_role" class="col-md-4 col-lg-3 col-form-label">Role:</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="user_role" type="text" class="form-control" id="user_role" value="{{ Auth::user()->user_role }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="country" class="col-md-4 col-lg-3 col-form-label">Country:</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="country" type="text" class="form-control" id="country" value="{{ $country }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email:</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger custom-submit-button">Save Changes</button>
                                    </div>
                                </form>
                                <!-- End Profile Edit Form -->
                            </div>
                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form method="POST" action="{{ route('admin.update.password') }}" id="passwordForm">
                                    {!! csrf_field() !!}
                                    <div class="row mb-3">
                                        <label for="old_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="old_password" type="password" class="form-control" id="old_password">
                                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}"/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="new_password" type="password" class="form-control" id="new_password">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="confirm_password" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="confirm_password" type="password" class="form-control" id="confirm_password">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger custom-submit-button">Change Password</button>
                                    </div>
                                </form>
                                <!-- End Change Password Form -->
                            </div>
                        </div>
                        <!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- End #main -->
@include('admin.layout.footer')
<script>
    $(document).ready(function () {

        $("#profileForm").validate({
            rules: {
                fullName: {
                    required: true,
                },
                country : {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            messages: {
                fullName: {
                    required: "Full name is required!",
                },
                country : {
                    required: 'Country is required!',
                },
                email : {
                    required: 'Email is required!',
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        
        $("#passwordForm").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password" // This ensures that confirm_password matches the value of new_password
                }
            },
            messages: {
                old_password: {
                    required: "Current password is required!",
                },
                new_password: {
                    required: "New password is required!",
                },
                confirm_password: {
                    required: 'Confirm password is required!',
                    equalTo: 'Passwords do not match!' // Custom message for non-matching passwords
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        const uploadButton = $('#upload-button');
        const removeButton = $('#remove-button');
        const profilePicInput = $('#profile_pic');
        const profilePicPreview = $('#profile-pic-preview');

        // Event listener for file input change
        profilePicInput.change(function (event) {
            const file = event.target.files[0];

            if (file) {
                // Display preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePicPreview.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                // Show remove button
                removeButton.show();
            }
        });

        // Event listener for remove button
        removeButton.click(function () {
            // Clear file input and hide preview
            profilePicInput.val('');
            profilePicPreview.attr('src', '{{ URL::to('public/assets/img/profile-img.jpg') }}');

            // Hide remove button
            removeButton.hide();
        });

        // Event listener for upload button
        uploadButton.click(function (event) {
            event.preventDefault();
            profilePicInput.click(); // Trigger the click on the file input
        });
    });
</script>
@include('admin.layout.end')