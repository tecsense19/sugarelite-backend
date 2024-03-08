<?php 
$test_or_live = $getPlans ? $getPlans->test_or_live ? $getPlans->test_or_live : '' : '';
?>
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .card-title {
        color: black;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Plans</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Plans</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        @include('flash-message')
        <!-- General Form Elements -->
        <form method="POST" class="profileForm" action="{{ route('admin.price.store') }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Test Account Plans</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">Stripe Product ID</label>
                                    <input type="text" class="form-control" name="test_product_id" id="test_product_id" value="{{ $getPlans ? $getPlans->test_product_id : '' }}" />
                                    <input type="hidden" class="form-control" name="settings_id" id="settings_id" value="{{ $getPlans ? $getPlans->id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">4 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="test_four_week_price_id" id="test_four_week_price_id" value="{{ $getPlans ? $getPlans->test_four_week_price_id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">12 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="test_twelve_week_price_id" id="test_twelve_week_price_id" value="{{ $getPlans ? $getPlans->test_twelve_week_price_id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">6 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="test_six_week_price_id" id="test_six_week_price_id" value="{{ $getPlans ? $getPlans->test_six_week_price_id : '' }}" />
                                </div>
                            </div>
                            <h5 class="card-title">Live Account Plans</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">Stripe Product ID</label>
                                    <input type="text" class="form-control" name="live_product_id" id="live_product_id" value="{{ $getPlans ? $getPlans->live_product_id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">4 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="live_four_week_price_id" id="live_four_week_price_id" value="{{ $getPlans ? $getPlans->live_four_week_price_id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">12 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="live_twelve_week_price_id" id="live_twelve_week_price_id" value="{{ $getPlans ? $getPlans->live_twelve_week_price_id : '' }}" />
                                </div>
                                <div class="col-sm-3">
                                    <label for="inputText" class="col-form-label">6 Week Stripe Price ID</label>
                                    <input type="text" class="form-control" name="live_six_week_price_id" id="live_six_week_price_id" value="{{ $getPlans ? $getPlans->live_six_week_price_id : '' }}" />
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-sm-12 d-flex align-items-center">
                                    <span class="me-2">Test</span>
                                    <label class="switch me-2">
                                        <input type="checkbox" name="test_or_live" id="test_or_live" value="{{ $test_or_live }}" <?php echo $test_or_live == "1" ? 'checked' : '' ?> />
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="me-2">Live</span>
                                </div>
                            </div>
                            <div class="row mt-5 mb-3">
                                <div class="col-sm-12 d-flex">
                                    <button type="submit" class="submit btn btn-primary custom-submit-button">Submit Form</button>
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
    $(document).ready(function () {
        $('.profileForm').submit(function(){
            // Disable submit button to prevent multiple submissions
            
        });

        $('input[name="test_or_live"]').change(function() {
            // Get the value of the checked state (0 if unchecked, 1 if checked)
            var value = $(this).is(':checked') ? 1 : 0;
            $("#test_or_live").val(value);
        });

        $(".profileForm").validate({
                rules: {
                    username: {
                        required: true,
                    }
                },
                messages: {
                username: {
                        required: "Full name is required!",
                    }
                },
                submitHandler: function(form) {
                    $('.spinner-image').show();
                    form.submit();
                }
        });
    });
</script>

@include('admin.layout.end')