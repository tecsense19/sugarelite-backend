<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Subscription</th>
            <th>Current Plan</th>
            <th>Subscribed Date</th>
            <th>Next Billing Date</th>
            <th>User Status</th>
            <th>Created At</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($userreportList) > 0)
            @foreach($userreportList as  $list_report)
                @php
                    $userId = Crypt::encryptString($list_report->id);

                    $planPrice = $list_report->getLastSubscription ? $list_report->getLastSubscription->plan_price : '-';
                    $planType = $list_report->getLastSubscription ? $list_report->getLastSubscription->plan_type : '-';
                    $currentPlan = '-';
                    if($planPrice && $planType)
                    {
                        $currentPlan = 'DKK '.$planPrice.' / '.$planType;
                    }

                    if($list_report->user_status != 'active')
                    {
                        $badgeColor = 'danger';
                    }
                    else
                    {
                        $badgeColor = 'success';
                    }
                @endphp
                <tr>
                    <td class="text-capitalize">{{ $list_report->username }}</td>
                    <td class="text-capitalize"><span class="badge bg-secondary">{{ $list_report->stripe_subscription_id }}</span></td>
                    <td class="text-capitalize">{{ $currentPlan }}</td>
                    <td class="text-capitalize">{{ $list_report->subscription_start_date ? date('d-m-Y', strtotime($list_report->subscription_start_date)) : '-' }}</td>
                    <td class="text-capitalize">{{ $list_report->next_subscription_date ? date('d-m-Y', strtotime($list_report->next_subscription_date)) : '-' }}</td>
                    <td class="text-capitalize"><span class="badge bg-{{ $badgeColor }}">{{ $list_report->user_status }}</span></td>
                    <td class="text-capitalize">{{ date('d-m-Y', strtotime($list_report->created_at)) }}</td>
                    <td class="text-center"><a href="{{ route('admin.subscriptions.view', ['id' => $userId]) }}"><i class="bi bi-eye-fill" title="view" style="cursor: pointer; font-size: 20px;"></i></a></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-capitalize text-center">Report Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>       
{!! $userreportList->links('pagination') !!}
<!-- End Table with stripped rows -->