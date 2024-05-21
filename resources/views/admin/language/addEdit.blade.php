
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
        <h1>Language</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Language</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        @include('flash-message')
        <!-- General Form Elements -->
        <form method="POST" class="profileForm" action="{{ route('admin.saveLanguage') }}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="card-title">Language Master</h5>
                        </div>
                        <div class="col-sm-6 pt-4 float-left">
                            <button type="button" class="btn btn-secondary" id="addButton">Add New Pair</button>
                        </div>
                    </div>
                    <div class="row">   
                        <div class="col-sm-4">
                            <label for="inputText" class="col-form-label">String</label>
                        </div>                          
                        <div class="col-sm-4">
                            <label for="inputText" class="col-form-label">Enter English String</label>
                        </div> 
                        <div class="col-sm-4">
                            <label for="inputText" class="col-form-label">Enter Danish String</label>
                        </div> 
                    </div> 
                    <div id="inputContainer"></div>      
                    <?php 
                    if (isset($getLanguage)) {
                    foreach ($getLanguage as $key => $value) { ?>
                        <div class="row"> 
                            
                            <div class="col-sm-4 mb-2">
                            <label for="inputText" class="col-form-label">{{ $getLanguage ? $value->var_string : '' }}</label> 
                            </div>
                            <div class="col-sm-4 mb-2">
                                <input type="text" required class="form-control" name="english_string[]" value="{{ $getLanguage ? $value->english_string : '' }}" />                                
                            </div>
                            <div class="col-sm-3 mb-2">
                                <input type="text" required class="form-control" name="danish_string[]" value="{{ $getLanguage ? $value->danish_string : '' }}" />
                            </div> 
                            <div class="col-sm-1 mb-2">                               
                                    <button type="submit" class="btn btn-secondary delete_data" data-id="{{ $value->id }}">Remove</button>
                                </form>
                            </div>  
                        </div> 
                    <?php }  } ?>
                    {!! $getLanguage->links('pagination') !!}          
                            <div class="row mt-5 mb-3">
                                <div class="col-sm-12 d-flex">
                                    <button type="submit" class="submit btn btn-primary custom-submit-button">Submit</button>
                                    <div class="spinner-image" style="display:none;">
                                        <img src="{{ URL::to('public/assets/img/Spinner.gif') }}" alt="" width="41px" >
                                    </div>
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

<script>
        document.addEventListener('DOMContentLoaded', function () {
            let counter = 1;
            document.getElementById('addButton').addEventListener('click', function () {
                counter++;
                let inputContainer = document.getElementById('inputContainer');
                let newInputPair = document.createElement('div');
                newInputPair.className = 'row input-pair mt-3';
                newInputPair.innerHTML = `
                    <div class="col-sm-4 mb-2 mb-3">
                       -                               
                    </div>
                    <div class="col-sm-4 mb-3">
                        <input type="text" class="form-control" name="english_string[]" id="english_string${counter}" value="" />             
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" class="form-control" name="danish_string[]" id="danish_string_${counter}" value="" />
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger removeButton">Remove</button>
                    </div>
                `;
                inputContainer.appendChild(newInputPair);
                attachRemoveButtonEvent(newInputPair.querySelector('.removeButton'));
            });

            function attachRemoveButtonEvent(button) {
                button.addEventListener('click', function () {
                    button.closest('.input-pair').remove();
                });
            }

            // Attach remove event to initial remove button
            document.querySelectorAll('.removeButton').forEach(button => {
                attachRemoveButtonEvent(button);
            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.delete_data').on('click', function(e) {
    e.preventDefault(); // Prevent default form submission
    var dataId = $(this).data('id');
    // Confirm deletion
    if (confirm('Are you sure you want to delete this language entry?')) {
        $.ajax({
            url: "{{ route('language.delete', ['id' => ':id']) }}".replace(':id', dataId),
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