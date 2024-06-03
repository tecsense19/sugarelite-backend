@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')

<?php 
//  echo '<pre>';print_r($userreportList);echo '</pre>';
//  die;

// foreach ($userreportList as $report) {
//     // Access sender user
//     $sender = $report->sender;
    
//     // Access receiver user
//     $receiver = $report->receiver;

   

// }
?>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>Spam User Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">List Of Spam User Report</li>
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
                        <h5 class="card-title" style="color:black;!important">Spam ReportList</h5>
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
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
                            <div class="d-flex gap-2">
                                <!-- <div class="form-group">
                                    <select id="user_status" name="user_status" class="form-control">
                                        <option value="">Select User Status</option>
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="date" id="from_date" name="from_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="date" id="to_date" name="to_date" class="form-control">
                                </div> -->
                                <div class="d-flex">
                                    <input name="search" id="search" class="form-control me-2" placeholder="Search"/>
                                    <button name="clear-button" id="clear-button" class="btn btn-danger clear-button">Clear</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content userReportList">
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
        ReportList();
        $('body').on('click', '.pagination a', function(e) 
        {
            e.preventDefault();

            var url = $(this).attr('href');
            getPerPageUserreportList(url);
        });
    });

    function getPerPageUserreportList(get_pagination_url) 
    {
        var search = $('#search').val();
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var user_status = $('#user_status').val();
        var entries_per_page = $('#entries-per-page').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:get_pagination_url,
            data: { search: search, from_date: fromDate, to_date: toDate, user_status: user_status, entries_per_page: entries_per_page },
            success:function(data)
            {
                $('.userReportList').html(data);
            }
        });   
    }

    function ReportList()
    {
        var search = $('#search').val();
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var user_status = $('#user_status').val();
        var entries_per_page = $('#entries-per-page').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("spamreport.list-userreport") }}',
            data: {search: search, from_date: fromDate, to_date: toDate, user_status: user_status, entries_per_page: entries_per_page},
            success:function(data)
            {
                $('.userReportList').html(data);
            }
        });
    }

    $('body').on('keyup', '#search', function (e) {
        ReportList();
    });

    $('body').on('click', '#clear-button', function(e) {
        $('#search').val('');
        $('#user_status').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        ReportList();
    });

    $('body').on('change', '#to_date', function (e) {
        ReportList();
    });
    
    $('body').on('change', '#user_status', function (e) {
        ReportList();
    });

    $('body').on('change', '#entries-per-page', function (e) {
        ReportList();
    });
</script>
@include('admin.layout.end')