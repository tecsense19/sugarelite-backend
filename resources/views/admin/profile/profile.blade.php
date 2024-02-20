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
    @include('flash-message')
    <section class="section">
    {!! csrf_field() !!}
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Datatables</h5>
                <div class="d-flex justify-content-end align-items-center">
                    <div class="d-flex">
                        <input name="search" id="search" class="form-control me-2" placeholder="Search Booking"/>
                        <button name="clear-button" id="clear-button" class="btn btn-danger">Clear</button>
                    </div>
                    <div>
                        
                    </div>
                </div>
              <div class="tab-content profileDataList">

              </div>
              
            </div>
          </div>

        </div>
      </div>
    </section>
<main>

<!-- End #main -->
@include('admin.layout.footer')


<script>
    $( document ).ready(function() 
    {
        profileList();

        $('body').on('click', '.pagination a', function(e) 
        {
            e.preventDefault();

            var url = $(this).attr('href');
            getPerPageProfileList(url);
        });
    });

    function getPerPageProfileList(get_pagination_url) 
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:get_pagination_url,
            data: { search: search },
            success:function(data)
            {
                $('.profileDataList').html(data);
            }
        });   
    }

    function profileList()
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("profile.list-profile") }}',
            data: { search: search },
            success:function(data)
            {
                $('.profileDataList').html(data);
            }
        });
    }

    $(document).ready(function () {
        $('.delete-profile').click(function (e) {
            e.preventDefault(); // Prevent the default action of the link
            var profileId = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'profile/delete/' + profileId,
                        type: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            Swal.fire('Deleted!', 'Your profile has been deleted.', 'success');
                            profileList
                        },
                        error: function (data) {
                            Swal.fire('Error!', 'An error occurred while deleting the profile.', 'error');
                        }
                    });
                }
            });
        });
    });

    function deleteprofile(profile_id)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this profile.",
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
                    type:'post',
                    headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                    url:'{{ route("profile.delete-profile") }}',
                    data: { profile_id: profile_id },
                    success:function(response)
                    {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#fe7d22',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                profileList();
                            }
                        });
                    }
                });
            }
        });
    }

    $('body').on('keyup', '#search', function (e) 
    {
        profileList();
    });

    $('body').on('click', '#clear-button', function(e) {
        $('#search').val('');
        profileList();
    });
</script>
@include('admin.layout.end')