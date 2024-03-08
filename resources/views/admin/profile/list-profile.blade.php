<!-- Table with stripped rows -->
<table class="table datatable">
<thead>
    <tr>
        <th>#</th>
        <th>Profile Pic</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(count($list_profiles) > 0)
        @foreach($list_profiles as $keys => $list_prof)
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
            <td>{{ $list_prof->user_status }}</td>
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