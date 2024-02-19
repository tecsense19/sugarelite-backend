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
              <form method="POST" action="{{ route('profile.add-profile') }}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">User Role</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="user_role" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Avatar</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="avatar_url" required>
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
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Submit Button</label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Submit Form</button>
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
@include('admin.layout.end')