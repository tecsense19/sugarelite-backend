<!-- Table with stripped rows -->
<table class="table datatable">
<thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Customer Id</th>
        <th>Subscription Id</th>
        <th>Plan Type</th>
        <th>Plan Price</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Next Billing Date</th>
        <th>Cancel Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(count($subscriptionList) > 0)
        @foreach($subscriptionList as $keys => $list_prof)
            @php
                $userId = Crypt::encryptString($list_prof->id);
                if($list_prof->is_subscription_cancel == '1')
                {
                    $badgeColor = 'danger';
                    $subscriptionStatus = 'Canceled';
                }
                else
                {
                    $badgeColor = $list_prof->is_subscription_stop == '0' ? 'success' : 'danger';
                    $subscriptionStatus = $list_prof->is_subscription_stop == '0' ? 'Active' : 'Stop';
                }
            @endphp
        <tr>
            <td>{{ ($keys+1) }}</td>
            <td>{{ $list_prof->username }}</td>
            <td><span class="badge bg-secondary">{{ $list_prof->stripe_customer_id }}</span></td>
            <td><span class="badge bg-secondary">{{ $list_prof->stripe_subscription_id }}</span></td>
            <td>{{ $list_prof->getLastSubscription->plan_type }}</td>
            <td>{{ $list_prof->getLastSubscription->plan_price }}</td>
            <td>{{ date('d-m-Y', strtotime($list_prof->subscription_start_date)) }}</td>
            <td>{{ date('d-m-Y', strtotime($list_prof->subscription_end_date)) }}</td>
            <td>{{ date('d-m-Y', strtotime($list_prof->next_subscription_date)) }}</td>
            <td>{{ $list_prof->subscription_cancel_date ? date('d-m-Y', strtotime($list_prof->subscription_cancel_date)) : '-' }}</td>
            <td><span class="badge bg-{{ $badgeColor }}">{{ $subscriptionStatus }}</span></td>
            <td><a href="{{ route('admin.subscriptions.view', ['id' => $userId]) }}"><i class="bi bi-eye-fill" title="view" style="cursor: pointer; font-size: 20px;"></i></a></td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="12" class="text-capitalize text-center">Subscriptions Not Found.</td>
        </tr>
    @endif
</tbody>
</table>
{!! $subscriptionList->links('pagination') !!}
<!-- End Table with stripped rows -->