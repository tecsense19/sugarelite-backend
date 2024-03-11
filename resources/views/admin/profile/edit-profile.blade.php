@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">General Form Elements</h5>
                        <!-- General Form Elements -->
                        @include('flash-message')
                        <form method="POST" class="profileForm" action="{{ route('profile.update-profile') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="inputText" class="col-form-label">Name</label>
                                    <input type="hidden" name="user_id" value="{{ $list_profiles->id }}">
                                    <input type="text" class="form-control" name="username" value="{{$list_profiles->username}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Avatar</label>
                                    <input class="form-control" type="file" id="formFile" name="avatar_url" accept="image/png, image/jpeg">
                                    <span class="error-message" style="color: red;"></span>
                                    <div>
                                        @if($list_profiles->avatar_url)
                                        <img src="{{ url('/').'/'.$list_profiles->avatar_url }}" height="30px" width="30px" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Public Image</label>
                                    <input class="form-control" type="file" id="formFile" name="public_images[]" multiple accept="image/png, image/jpeg">
                                    <span class="error-message" style="color: red;"></span>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach ($getimage as $imgs) 
                                            @if($imgs->image_type == 'public')
                                                <div class="display_img" data-id ="{{$imgs->id}}">
                                                    <img src="{{ $imgs->public_images }}" height="30px" width="30px" alt="">
                                                    <a class="img_remove" data-id ="{{$imgs->id}}"><i class="ri-close-circle-line"></i></a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Private Image</label>
                                    <input class="form-control" type="file" id="formFile" name="total_private_images[]" multiple accept="image/png, image/jpeg">
                                    <span class="error-message" style="color: red;"></span>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach ($getimage as $imgs) 
                                            @if($imgs->image_type == 'private')
                                                <div class="display_img" data-id ="{{$imgs->id}}">
                                                    <img src="{{ $imgs->public_images }}" height="30px" width="30px" alt="">
                                                    <a class="img_remove" data-id ="{{$imgs->id}}"><i class="ri-close-circle-line"></i></a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Sex</label>
                                    <div class="d-flex">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="sex" id="sex1" value="male" {{$list_profiles->sex == 'male' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="sex1">
                                            Male
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="sex" id="sex2" value="female" {{$list_profiles->sex == 'female' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="sex2">
                                            Female
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="sex" id="sex" value="other" {{$list_profiles->sex == 'other' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="sex3">
                                            Others
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputEmail" class="col-form-label">Hight</label>
                                    <input type="text" class="form-control" name="height" value="{{$list_profiles->height}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Premium</label>
                                    <div class="d-flex">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="premium" id="premium1" value="true" {{$list_profiles->premium == 'true' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="premium1">
                                            True
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="premium" id="premium2" value="false" {{$list_profiles->premium == 'false' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="premium2">
                                            False
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Age</label>
                                    <input type="number" class="form-control" name="age" value="{{$list_profiles->age}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Weight</label>
                                    <input type="number" class="form-control" name="weight" value="{{$list_profiles->weight}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Country</label>
                                    <input type="text" class="form-control" name="country" value="{{$list_profiles->country}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Sugar Type</label>
                                    <input type="text" class="form-control" name="sugar_type" value="{{$list_profiles->sugar_type}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputDate" class="col-form-label">Birth Date</label>
                                    <input type="date" class="form-control" name="birthdate" value="{{$list_profiles->birthdate}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputEmail" class="col-form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{$list_profiles->email}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Region</label>
                                    <input type="text" class="form-control" name="region" value="{{$list_profiles->region}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Bio</label>
                                    <input type="text" class="form-control" name="bio" value="{{$list_profiles->bio}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Ethnicity</label>
                                    <input type="text" class="form-control" name="ethnicity" value="{{$list_profiles->ethnicity}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Body Structure</label>
                                    <input type="text" class="form-control" name="body_structure" value="{{$list_profiles->body_structure}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Hair Color</label>
                                    <input type="text" class="form-control" name="hair_color" value="{{$list_profiles->hair_color}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Piercings</label>
                                    <div class="d-flex">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="piercings" id="piercings1" value="yes" {{$list_profiles->piercings == 'yes' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="piercings1">
                                            Yes
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="piercings" id="piercings2" value="no" {{$list_profiles->piercings == 'no' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="piercings2">
                                            No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Tattoos</label>
                                    <div class="d-flex">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="tattoos" id="tattoos1" value="yes" {{$list_profiles->tattoos == 'yes' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="tattoos1">
                                            Yes
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="tattoos" id="tattoos2" value="no" {{$list_profiles->tattoos == 'no' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="tattoos2">
                                            No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword" class="col-form-label">Education</label>
                                    <input type="text" class="form-control" name="education" value="{{$list_profiles->education}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Smoking</label>
                                    <input type="text" class="form-control" name="smoking" value="{{$list_profiles->smoking}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Drinks</label>
                                    <input type="text" class="form-control" name="drinks" value="{{$list_profiles->drinks}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Employment</label>
                                    <input type="text" class="form-control" name="employment" value="{{$list_profiles->employment}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputNumber" class="col-form-label">Civil Status</label>
                                    <input type="text" class="form-control" name="civil_status" value="{{$list_profiles->civil_status}}" required>
                                </div>
                                <div class="col-sm-10">
                                    <label for="inputPassword" class="col-form-label">User Status</label>
                                    <div class="d-flex">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="user_status" id="user_status" value="active" {{$list_profiles->user_status == 'active' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="user_status">
                                            Active
                                            </label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="user_status" id="user_status" value="deactive" {{$list_profiles->user_status == 'deactive' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="user_status">
                                            Deactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="row mt-5 mb-3">
                                <div class="col-sm-12 d-flex">
                                    <button type="submit" class="submit btn btn-primary custom-submit-button">Update Form</button>
                                    <div class="spinner-image" style="display:none;">
                                        <img src="{{ URL::to('public/assets/img/Spinner.gif') }}" alt="" width="41px" >
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(".img_remove").on('click', function() {
        var userimageid = $(this).data('id');
        var url = "{{ url('/remove-user-images') }}";
        console.log(url);
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this image.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#fe7d22',
            cancelButtonText: 'No',
            cancelButtonColor: '#d33',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: url, 
                    data: {
                        id: userimageid,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        $("div[data-id='" + userimageid + "']").remove();
                    },
                    error: function(error) {
                        // Handle error
                        console.log(error);
                    }
                });
            }
        });
    });
</script>
<script>
  $(document).ready(function () {

    $(".profileForm").validate({
            rules: {
              username: {
                    required: true,
                },
                user_role: {
                    required: true,
                },
                // avatar_url : {
                //     required: true,
                // },
                // public_images : {
                //     required: true,
                // },
                // total_private_images : {
                //     required: true,
                // },
                height : {
                    required: true,
                },
                age : {
                    required: true,
                },
                weight: {
                    required: true,
                },
                country: {
                    required: true,
                },
                sugar_type: {
                    required: true,
                },
                birthdate: {
                    required: true,
                    maxToday: true
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
                region: {
                    required: true,
                },
                bio: {
                    required: true,
                },
                ethnicity: {
                    required: true,
                },
                body_structure: {
                    required: true,
                },
                hair_color: {
                    required: true,
                },
                education: {
                    required: true,
                },
                smoking: {
                    required: true,
                },
                drinks: {
                    required: true,
                },
                employment: {
                    required: true,
                },
                civil_status: {
                    required: true,
                },
            },
            messages: {
              username: {
                    required: "Full name is required!",
                },
                user_role: {
                    required: "User Role is required!",
                },
                // avatar_url : {
                //     required: 'Profile Picture is required!',
                // },
                // public_images : {
                //     required: 'Public Picture is required!',
                // },
                // total_private_images : {
                //     required: 'Private Picture is required!!',
                // },
                height : {
                    required: 'Height no is required!',
                },
                age : {
                    required: 'Age is required!',
                },
                weight : {
                    required: 'Weight is required!',
                },
                sugar_type: {
                    required: 'Sugar Type is required!',
                },
                birthdate: {
                    required: 'Birthdate is required!',
                    maxToday: "Birth date should be today or before today!"
                },
                email: {
                    required: 'Email is required!',
                },
                password: {
                    required: 'Password is required!',
                },
                country: {
                    required: 'Country is required!',
                },
                region: {
                    required: 'Region is required!',
                },
                bio: {
                    required: 'Bio is required!',
                },
                ethnicity: {
                    required: 'Ethnicity is required!',
                },
                body_structure: {
                    required: 'Body Structure is required!',
                },
                hair_color: {
                    required: 'Hair Color is required!',
                },
                education: {
                    required: 'Education is required!',
                },
                smoking: {
                    required: 'Smoking is required!',
                },
                drinks: {
                    required: 'Drinks is required!',
                },
                employment: {
                    required: 'Employment is required!',
                },
                civil_status: {
                    required: 'Civil Status is required!',
                },
            },
            submitHandler: function(form) {
                $('.spinner-image').show();
                form.submit();
            }
    });
    $.validator.addMethod("maxToday", function(value, element) {
        var today = new Date();
        var inputDate = new Date(value);
        return inputDate <= today;
    }, "Please specify a date before today.");

    // button disable
  });

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all file inputs
        var fileInputs = document.querySelectorAll('input[type="file"]');

        // Function to validate file extension and display error message
        function validateFileExtension(fileInput) {
            var validExtensions = ['jpg', 'jpeg', 'png'];
            var fileName = fileInput.value;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            var errorSpan = fileInput.parentElement.querySelector('.error-message');

            if (validExtensions.indexOf(fileExtension) == -1) {
                errorSpan.textContent = "Please select a file with a valid extension (jpg, jpeg, png).";
                fileInput.value = ''; // Clear the input field
                return false;
            } else {
                errorSpan.textContent = ""; // Clear the error message
            }

            return true;
        }

        // Attach change event listener to file inputs
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                validateFileExtension(input);
            });
        });
    });
</script>
<!-- End #main -->
@include('admin.layout.footer')
@include('admin.layout.end')