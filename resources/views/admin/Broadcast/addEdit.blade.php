
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .card-title {
    padding: 20px 0 15px 0;
    font-size: 18px;
    font-weight: 500;
    color: #000;
    font-family: "Poppins", sans-serif;
}
.float-left {
    justify-content: end;
    display: flex;
}
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Broadcast Message</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Broadcast</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        @include('flash-message')
        <!-- General Form Elements -->
        <form method="POST" class="profileForm" action="{{ route('admin.savebroadcast') }}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="card-title">Broadcast</h5>
                        </div>
                       
                    </div>
                    <div class="row">                                                  
                        <div class="col-sm-4">
                            <label for="inputText" class="col-form-label">Enter Message</label>
                        </div>                        
                    </div> 

                        <div class="row">                             
                            <div class="col-sm-11 mb-2">
                                <textarea required class="form-control" name="broadcast_message"></textarea> 
                                <input type="hidden" value="broadcast" name="broadcast">                                   
                            </div>
                           
                            <div class="col-sm-1 mb-2">                               
                                    <button type="submit" class="btn btn-secondary" data-id="">Send</button>
                                </form>
                            </div>  
                        </div> 
                    
                        <div class="col-sm-6">
                            <h5 class="card-title">Broadcast Data</h5>
                        </div>
                      
                    <?php 
                    if ($userElitesupport) {
                    foreach ($userElitesupport as $key => $value) { ?>
                        <div class="row">             
                        
                            <div class="col-sm-11 mb-2">
                                <textarea readonly class="form-control" name="">{{ $userElitesupport ? $value->description : '' }}</textarea>      
                                                      
                            </div>
                           
                            <div class="col-sm-1 mb-2">                               
                                    <button type="submit" class="btn btn-secondary delete_data" data-id="{{ $value->id }}">Remove</button>
                                </form>
                            </div>  
                        </div> 
                    <?php } } else { ?>
                        <div class="col-sm-12 text-center">
                            <label for="nodata">No data found</label>   
                        </div>
                    <?php } ?>
                    {!! $userElitesupport->links('pagination') !!}          
                            <div class="row mt-5 mb-3">
                                <div class="col-sm-12 d-flex">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- End General Form Elements -->
    </section>
</main>

<!-- End #main -->
@include('admin.layout.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.delete_data').on('click', function(e) {
    e.preventDefault(); // Prevent default form submission
    var dataId = $(this).data('id');
    // Confirm deletion
    if (confirm('Are you sure you want to delete this broadcast entry?')) {
        $.ajax({
            url: "{{ route('broadcast.delete', ['id' => ':id']) }}".replace(':id', dataId),
            method: 'GET',
            data: { id: dataId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle success response
                window.location.reload();
                // Optionally, you can reload the page or remove the deleted entry from the DOM
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
                alert('Error occurred while deleting the language entry.');
            }
        });
    }
});

});
</script>

@include('admin.layout.end')