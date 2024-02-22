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
              <form method="POST" action="{{ route('profile.update-profile') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" value="{{$list_profiles->username}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">User Role</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="user_role" value="{{$list_profiles->user_role}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Avatar</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="avatar_url">
                    <img src="{{$list_profiles->avatar_url}}" height="30px" width="30px" alt="">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Public Image</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="public_images[]" multiple>
                      <div class="d-flex gap-2 mt-2">
                        @foreach ($getimage as $imgs) 
                          @if($imgs->image_type == 'public')
                          <div class="display_img" data-id ="{{$imgs->id}}">
                              <img src="{{$imgs->public_images}}" height="30px" width="30px" alt="">
                              <a class="img_remove" data-id ="{{$imgs->id}}"><i class="ri-close-circle-line"></i></a>
                          </div>
                          @endif
                        @endforeach
                      </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Private Image</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="total_private_images[]" multiple>
                    <div class="d-flex gap-2 mt-2">
                      @foreach ($getimage as $imgs) 
                        @if($imgs->image_type == 'private')
                        <div class="display_img" data-id ="{{$imgs->id}}">
                            <img src="{{$imgs->public_images}}" height="30px" width="30px" alt="">
                            <a class="img_remove" data-id ="{{$imgs->id}}"><i class="ri-close-circle-line"></i></a>
                        </div>
                        @endif
                      @endforeach
                    </div>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Sex</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex1" value="male" {{$list_profiles->sex == 'male' ? 'checked' : ''}}>
                      <label class="form-check-label" for="sex1">
                      Male
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex2" value="female" {{$list_profiles->sex == 'female' ? 'checked' : ''}}>
                      <label class="form-check-label" for="sex2">
                      Female
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sex" id="sex" value="other" {{$list_profiles->sex == 'other' ? 'checked' : ''}}>
                      <label class="form-check-label" for="sex3">
                      Others
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Hight</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="height" value="{{$list_profiles->height}}" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Premium</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="premium" id="premium1" value="true" {{$list_profiles->premium == 'true' ? 'checked' : ''}}>
                      <label class="form-check-label" for="premium1">
                      True
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="premium" id="premium2" value="false" {{$list_profiles->premium == 'false' ? 'checked' : ''}}>
                      <label class="form-check-label" for="premium2">
                      False
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Age</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="age" value="{{$list_profiles->age}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Weight</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="weight" value="{{$list_profiles->weight}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Country</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="country" value="{{$list_profiles->country}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Sugar Type</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="sugar_type" value="{{$list_profiles->sugar_type}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Birth Date</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="birthdate" value="{{$list_profiles->birthdate}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" value="{{$list_profiles->email}}" required>
                  </div>
                </div>
                <!-- <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" required>
                  </div>
                </div> -->
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Region</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="region" value="{{$list_profiles->region}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Bio</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="bio" value="{{$list_profiles->bio}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Ethnicity</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="ethnicity" value="{{$list_profiles->ethnicity}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Body Structure</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="body_structure" value="{{$list_profiles->body_structure}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Hair Color</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="hair_color" value="{{$list_profiles->hair_color}}" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Piercings</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="piercings" id="piercings1" value="yes" {{$list_profiles->piercings == 'yes' ? 'checked' : ''}}>
                      <label class="form-check-label" for="piercings1">
                      Yes
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="piercings" id="piercings2" value="no" {{$list_profiles->piercings == 'no' ? 'checked' : ''}}>
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
                      <input class="form-check-input" type="radio" name="tattoos" id="tattoos1" value="yes" {{$list_profiles->tattoos == 'yes' ? 'checked' : ''}}>
                      <label class="form-check-label" for="tattoos1">
                      Yes
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tattoos" id="tattoos2" value="no" {{$list_profiles->tattoos == 'no' ? 'checked' : ''}}>
                      <label class="form-check-label" for="tattoos2">
                      No
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Education</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="education" value="{{$list_profiles->education}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Smoking</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="smoking" value="{{$list_profiles->smoking}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Drinks</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="drinks" value="{{$list_profiles->drinks}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Employment</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="employment" value="{{$list_profiles->employment}}" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Civil Status</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="civil_status" value="{{$list_profiles->civil_status}}" required>
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">User Status</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="user_status" id="user_status" value="active" {{$list_profiles->user_status == 'active' ? 'checked' : ''}}>
                      <label class="form-check-label" for="user_status">
                      Active
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="user_status" id="user_status" value="deactive" {{$list_profiles->user_status == 'deactive' ? 'checked' : ''}}>
                      <label class="form-check-label" for="user_status">
                      Deactive
                      </label>
                    </div>
                  </div>
                </fieldset>
                <input type="hidden" name="user_id" value="{{ $list_profiles->id }}">
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Submit Button</label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary custom-submit-button">Update Form</button>
                  </div>
                </div>
              </form><!-- End General Form Elements -->

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
        var isConfirmed = confirm('Are you sure you want to remove this PDF?');
        if(isConfirmed){
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
</script>

<!-- End #main -->
@include('admin.layout.footer')
@include('admin.layout.end')