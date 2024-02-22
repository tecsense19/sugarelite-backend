@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
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
              <form method="POST" class="profileForm" action="{{ route('profile.add-profile') }}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">User Role</label>
                  <div class="col-sm-10">
                      <select id="entries-per-page" class="form-control" name="user_role" required>
                        <option value="user">User</option>
                      </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Avatar</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="avatar_url" accept="image/png, image/jpeg" required>
                    <span class="error-message" style="color: red;"></span>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Public Images</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="public_images[]" accept="image/png, image/jpeg" multiple required>
                    <span class="error-message" style="color: red;"></span>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Private Images</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="total_private_images[]" accept="image/png, image/jpeg" multiple required>
                    <span class="error-message" style="color: red;"></span>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Sex</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex1" value="male" checked>
                      <label class="form-check-label" for="sex1">
                      Male
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex2" value="female">
                      <label class="form-check-label" for="sex2">
                      Female
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex" value="other">
                      <label class="form-check-label" for="sex3">
                      Others
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Hight</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="height" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Premium</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="premium" id="premium1" value="true" checked>
                      <label class="form-check-label" for="premium1">
                      True
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="premium" id="premium2" value="false">
                      <label class="form-check-label" for="premium2">
                      False
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Age</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="age" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Weight</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="weight" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Country</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="country" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Sugar Type</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="sugar_type" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Birth Date</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="birthdate" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Region</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="region" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Bio</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="bio" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Ethnicity</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="ethnicity" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Body Structure</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="body_structure" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Hair Color</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="hair_color" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Piercings</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="piercings" id="piercings1" value="yes" checked>
                      <label class="form-check-label" for="piercings1">
                      Yes
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="piercings" id="piercings2" value="no">
                      <label class="form-check-label" for="piercings2">
                      No
                      </label>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Tattoos</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tattoos" id="tattoos1" value="yes" checked>
                      <label class="form-check-label" for="tattoos1">
                      Yes
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tattoos" id="tattoos2" value="no">
                      <label class="form-check-label" for="tattoos2">
                      No
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Education</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="education" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Smoking</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="smoking" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Drinks</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="drinks" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Employment</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="employment" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Civil Status</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="civil_status" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">User Status</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="user_status" id="user_status" value="active" checked>
                      <label class="form-check-label" for="user_status">
                      Active
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="user_status" id="user_status" value="deactive">
                      <label class="form-check-label" for="user_status">
                      Deactive
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Submit Button</label>
                  <div class="col-sm-10">
                    <button type="submit" class="submit btn btn-primary custom-submit-button">Submit Form</button>
                  </div>
                </div>
              </form><!-- End General Form Elements -->

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
    $(".profileForm").validate({
            rules: {
              username: {
                    required: true,
                },
                user_role: {
                    required: true,
                },
                avatar_url : {
                    required: true,
                },
                public_images : {
                    required: true,
                },
                total_private_images : {
                    required: true,
                },
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
                avatar_url : {
                    required: 'Profile Picture is required!',
                },
                public_images : {
                    required: 'Public Picture is required!',
                },
                total_private_images : {
                    required: 'Private Picture is required!!',
                },
                height : {
                    required: 'Height is required!',
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
                form.submit();
            }
    });
    $.validator.addMethod("maxToday", function(value, element) {
        var today = new Date();
        var inputDate = new Date(value);
        return inputDate <= today;
    }, "Please specify a date before today.");


    $('.profileForm').submit(function(){
        // Disable submit button to prevent multiple submissions
        $('.submit').prop('disabled', true);
    });
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

@include('admin.layout.end')