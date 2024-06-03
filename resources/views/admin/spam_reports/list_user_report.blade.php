
<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th>#No</th>
            <th>UserName</th>
            <th>Spam-Report user</th>
            <th>Message</th>          
        </tr>
    </thead>
    <tbody>
        @if(count($userreportList) > 0)
            @foreach($userreportList as $key => $list_report)
                
                @php
                    $userId = Crypt::encryptString($list_report->id);
                    $sender = $list_report->sender;
                    $receiver = $list_report->receiver;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="text-capitalize">{{ $sender->username }}</td>
                    <td class="text-capitalize"><span class="badge bg-danger">ID-{{ $receiver->id }} {{ $receiver->username }}</span></td>
                    <td class="text-capitalize">{{ $list_report->reason }}</td>                                       
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-capitalize text-center">Spam Report Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>       
{!! $userreportList->links('pagination') !!}
<!-- End Table with stripped rows -->