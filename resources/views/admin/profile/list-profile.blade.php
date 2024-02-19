@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">List Of Profile</li>
            </ol>
        </nav>
    </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Datatables</h5>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      <b>N</b>ame
                    </th>
                    <th>Email</th>
                    <th>User Role</th>
                    <th>Profile Pic</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($list_profiles as  $list_prof)
                  <tr>
                    <td>{{$list_prof->username}}</td>
                    <td>{{$list_prof->email}}</td>
                    <td>{{$list_prof->user_role}}</td>
                    <td><img height="30px" width="30px" src="{{$list_prof->avatar_url}}" alt=""></td>
                    <td>
                        <a href="{{route('profile.edit-profile',$list_prof->id)}}"><i class="bi bi-pencil-square"></i></a>
                        <a href="{{route('profile.delete-profile',$list_prof->id)}}"><i class="ri-delete-bin-6-fill"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
<main>


<!-- End #main -->
@include('admin.layout.footer')
@include('admin.layout.end')