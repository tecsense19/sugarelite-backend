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
                    <h5 class="card-title" style="color:black;!important">ProfileList</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="pagination-controls">
                            <select id="entries-per-page" name="pagination" style="padding:6px !important;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15" selected>15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            </select>
                            <label for="">Entries per page:</label>
                        </div>
                        <div class="d-flex">
                            <input name="search" id="search" class="form-control me-2" placeholder="Search"/>
                            <button name="clear-button" id="clear-button" class="btn btn-danger clear-button">Clear</button>
                        </div>
                    </div>
                    <div class="tab-content profileDataList">

                    </div>
                </div>
            </div>

        </div>
      </div>
    </section>
</main>
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
        var entries_per_page = $('#entries-per-page').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:get_pagination_url,
            data: { search: search, entries_per_page: entries_per_page },
            success:function(data)
            {
                $('.profileDataList').html(data);
            }
        });   
    }

    function profileList()
    {
        var search = $('#search').val();
        var entries_per_page = $('#entries-per-page').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("profile.list-profile") }}',
            data: { search: search, entries_per_page: entries_per_page },
            success:function(data)
            {
                $('.profileDataList').html(data);
            }
        });
    }

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

    $('body').on('change', '#entries-per-page', function (e) 
    {
        profileList();
    });
</script>

@include('admin.layout.end')