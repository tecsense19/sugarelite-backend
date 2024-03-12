<!-- Table with stripped rows -->
<table class="table datatable">
<thead>
    <tr>
        <th>#</th>
        <th>Profile Pic</th>
        <th>Name</th>
        <th>Email</th>
        <th>Birth Date</th>
        <th>Gender</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(count($list_profiles) > 0)
        @foreach($list_profiles as $keys => $list_prof)
            @php
                if($list_prof->user_status != 'active')
                {
                    $badgeColor = 'danger';
                }
                else
                {
                    $badgeColor = 'success';
                }
            @endphp
            <tr>
                <td>{{ ($keys+1) }}</td>
                <td>
                    @if($list_prof->avatar_url)
                        <img src="{{ url('/').'/'.$list_prof->avatar_url }}" alt="" style="height: 50px; width: 50px;">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $list_prof->username }}</td>
                <td>{{ $list_prof->email }}</td>
                <td>{{ $list_prof->birthdate ? date('d-m-Y', strtotime($list_prof->birthdate)) : '-' }}</td>
                <td class="text-capitalize"><span class="badge rounded-pill bg-dark">{{ $list_prof->sex }}</span></td>
                <td class="text-capitalize"><span class="badge bg-{{ $badgeColor }}">{{ $list_prof->user_status }}</span></td>
                <td>
                    @php
                        $encrypted_id = encrypt($list_prof->id);
                    @endphp
                    <a href="{{route('profile.edit-profile',['id' => $encrypted_id])}}"><i class="bi bi-pencil-square"></i></a>
                    <a href="#" class="delete-profile" onclick="deleteprofile('{{ $list_prof->id }}')" ><i class="ri-delete-bin-6-fill"></i></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="text-capitalize text-center">Profile Not Found.</td>
        </tr>
    @endif
</tbody>
</table>
{!! $list_profiles->links('pagination') !!}
<!-- End Table with stripped rows -->