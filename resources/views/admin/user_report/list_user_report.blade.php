<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
        <th>
            <b>N</b>ame
        </th>
        <th>Subscription</th>
        <th>User Status</th>
        <th>Register Date</th>
        <th>Payment Verification</th>
        <th>Payment Recurring Date</th>
        </tr>
    </thead>
    <tbody>
        @if(count($userreportList) > 0)
            @foreach($userreportList as  $list_report)
                <tr>
                    <td class="text-capitalize">{{$list_report->getusers->username}}</td>
                    <td class="text-capitalize">{{$list_report->subscription}}</td>
                    <td class="text-capitalize">{{$list_report->getusers->user_status}}</td>
                    <td class="text-capitalize">{{$list_report->register_date}}</td>
                    <td class="text-capitalize">{{$list_report->payment_verification}}</td>
                    <td class="text-capitalize">{{$list_report->payment_recurring_date}}</td>
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