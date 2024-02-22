<!-- Table with stripped rows -->
<table class="table datatable">
  <thead>
    <tr>
      <th>
        <b>N</b>ame
      </th>
      <th>Email</th>
      <th>User Role</th>
      <th>Profile Pic</th>
      <th>User Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($list_profiles as  $list_prof)
      <tr>
        <td class="text-capitalize">{{ $list_prof->username }}</td>
        <td class="text-capitalize">{{ $list_prof->email }}</td>
        <td class="text-capitalize">{{ $list_prof->user_role }}</td>
        <td class="text-capitalize"><img height="30px" width="30px" src="{{$list_prof->avatar_url}}" alt=""></td>
        <td class="text-capitalize">{{ $list_prof->user_status }}</td>
        <td>
            <a href="{{route('profile.edit-profile',$list_prof->id)}}"><i class="bi bi-pencil-square"></i></a>
            <a href="#" class="delete-profile" onclick="deleteprofile('{{ $list_prof->id }}')" ><i class="ri-delete-bin-6-fill"></i></a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{!! $list_profiles->links('pagination') !!}
<!-- End Table with stripped rows -->