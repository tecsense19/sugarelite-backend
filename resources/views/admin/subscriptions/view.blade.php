<?php 
$profilePic = $subscriptionDetails->avatar_url ? url('/').'/'.$subscriptionDetails->avatar_url : '';
$planPrice = $subscriptionDetails->getLastSubscription ? $subscriptionDetails->getLastSubscription->plan_price : '';
$planType = $subscriptionDetails->getLastSubscription ? $subscriptionDetails->getLastSubscription->plan_type : '';

$stripeSubscriptionId = $subscriptionDetails->stripe_subscription_id ? $subscriptionDetails->stripe_subscription_id : '';

$subscriptionStartDate = $subscriptionDetails->subscription_start_date ? date('d M Y', strtotime($subscriptionDetails->subscription_start_date)) : '';
$subscriptionEndDate = '';
$nextSubscriptionDate = $subscriptionDetails->subscription_start_date ? date('d M Y', strtotime($subscriptionDetails->next_subscription_date)) : '';;

if($subscriptionDetails->is_subscription_cancel == '1')
{
    $badgeColor = 'danger';
    $subscriptionStatus = 'Canceled';
}
else
{
    $badgeColor = $subscriptionDetails->is_subscription_stop == '0' ? 'success' : 'danger';
    $subscriptionStatus = $subscriptionDetails->is_subscription_stop == '0' ? 'Active' : 'Stop';
}

$cardType = $getCardDetails ? $getCardDetails->brand : '';
$expMonth = $getCardDetails ? $getCardDetails->exp_month : '';
$expYear = $getCardDetails ? $getCardDetails->exp_year : '';
?>
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .contact .info-box h3 {
        color: black;
    }

    .separator {
        display: block;
        height: 0;
        border-bottom: 1px solid var(--bs-border-color);
        border-bottom-style: dashed;
        border-bottom-color: var(--bs-border-dashed-color);
    }

    .text-gray-500 {
        color: #99A1B7 !important;
    }
    .text-gray-600 {
        color: #78829D !important;
    }
    .text-gray-800 {
        color: #252F4A !important;
    }
    .text-gray-900 {
        color: #071437 !important;
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>View Subscriptions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions') }}">List of Subscriptions</a></li>
                <li class="breadcrumb-item active">View Subscriptions</li>
            </ol>
        </nav>
    </div>
    @include('flash-message')
    {!! csrf_field() !!}
    <section class="section contact">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="info-box card flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                            <h3 class="mt-0">Summary</h3>
                            <div class="d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-60px symbol-circle me-3">
                                    <img alt="Pic" src="{{ $profilePic }}" style="with: 50px; height: 50px;">
                                </div>
                                <!--end::Avatar-->

                                <!--begin::Info-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <a href="#" class="fs-6 fw-bold text-gray-900 text-hover-primary me-2">{{ $subscriptionDetails->username }}</a>
                                    <!--end::Name-->

                                    <!--begin::Email-->
                                    <a href="#" class="text-gray-600 text-hover-primary">{{ $subscriptionDetails->email }}</a>
                                    <!--end::Email-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <div class="separator mt-3 mb-3"></div>
                            <div class="mb-3">
                                <!--begin::Title-->
                                <h3 class="mt-0">Product details</h3>
                                <!--end::Title-->

                                <!--begin::Details-->
                                <div class="mb-0">
                                    <!--begin::Plan-->
                                    <span class="badge bg-secondary me-2">Basic Bundle</span>
                                    <!--end::Plan-->

                                    <!--begin::Price-->
                                    <span class="fw-semibold text-gray-600">DKK {{ $planPrice }} / {{ $planType }}</span>
                                    <!--end::Price-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <div class="separator mt-3 mb-3"></div>
                            <div class="mb-3">
                                <!--begin::Title-->
                                <h3 class="mt-0">Payment Details</h3>
                                <!--end::Title-->

                                <!--begin::Details-->
                                <div class="mb-0">
                                    <!--begin::Plan-->
                                    <span class="fw-semibold text-gray-600 d-flex align-items-center">{{ $cardType }}</span>
                                    <span class="fw-semibold text-gray-600 d-flex align-items-center">Expires {{ $expMonth }}/{{ $expYear }}</span>
                                    <!--end::Plan-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <div class="separator mt-3 mb-3"></div>
                            <div class="mb-3">
                                <!--begin::Title-->
                                <h3 class="mt-0">Subscription Details</h3>
                                <!--end::Title-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <span class="fw-semibold text-gray-500 d-flex align-items-center py-1">Subscription ID:</span>
                                            <span class="fw-semibold text-gray-800 d-flex align-items-center py-2"><span class="badge bg-secondary me-2">{{ $stripeSubscriptionId }}</span></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex">
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-500 d-flex align-items-center pb-2">Started:</span>
                                        </div>
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-800">{{ $subscriptionStartDate }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex">
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-500 d-flex align-items-center pb-2">Status:</span>
                                        </div>
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-800"><span class="badge bg-{{ $badgeColor }}">{{ $subscriptionStatus }}</span></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex">
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-500 d-flex align-items-center pb-2">Next Invoice:</span>
                                        </div>
                                        <div class="mb-0 col-md-6">
                                            <span class="fw-semibold text-gray-800">{{ $nextSubscriptionDate }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="info-box card flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">                          
                            <div class="mb-3">
                                <!--begin::Title-->
                                <h3 class="mt-0">Subscribed Products:</h3>
                                <!--end::Title-->

                                <div class="table-responsive">
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                        <!--begin::Row-->
                                        <thead>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">PRODUCT</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">SUBSCRIPTION ID</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">QTY</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">TOTAL</th>
                                            <!-- <th class="text-gray-500" style="font-size: .90rem !important;">ACTION</th> -->
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label class="w-150px">Basic Bundle</label>
                                                    <div class="fw-normal text-gray-600">Basic {{ $planType }} bundle</div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $stripeSubscriptionId }}</span></td>
                                                <td>1</td>
                                                <td>DKK {{ $planPrice }} / {{ $planType }}</td>
                                                <!-- <td class="text-end"></td> -->
                                            </tr>
                                            <!--end::Row-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="info-box card flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                            <div class="mb-3">
                                <!--begin::Title-->
                                <h3 class="mt-0">Invoices:</h3>
                                <!--end::Title-->

                                <div class="table-responsive">
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                        <!--begin::Row-->
                                        <thead>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">ORDER ID</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">AMOUNT</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">STATUS</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">DATE</th>
                                            <th class="text-gray-500" style="font-size: .90rem !important;">INVOICE</th>
                                        </thead>
                                        <tbody>
                                            @foreach($getAllInvoice as $invoice)
                                            <tr>
                                                <td><a href="#" class="text-gray-600 text-hover-primary"><span class="badge bg-secondary">{{ $invoice->id }}<span></a></td>
                                                <td class="text-success">DKK {{ $invoice->amount_due / 100 }}</td>
                                                <td><span class="badge bg-success">{{ $invoice->status }}</span></td>
                                                <td>{{ date('d M Y', $invoice->created) }}</td>
                                                <td class=""><a href="{{ $invoice->invoice_pdf }}" class="btn btn-sm btn-light btn-active-light-primary">Download</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- End #main -->
@include('admin.layout.footer')
@include('admin.layout.end')