
<style>
  @supports (-webkit-appearance: none) or (-moz-appearance: none) {
    .checkbox-wrapper-13 input[type=checkbox] {
      --active: #F16667;
      --active-inner: #fff;
      --focus: 2px rgba(39, 94, 254, .3);
      --border: #BBC1E1;
      --border-hover: #F16667;
      --background: #fff;
      --disabled: #F6F8FF;
      --disabled-inner: #E1E6F9;
      -webkit-appearance: none;
      -moz-appearance: none;
      height: 21px;
      outline: none;
      display: inline-block;
      vertical-align: top;
      position: relative;
      margin: 0;
      cursor: pointer;
      border: 1px solid var(--bc, var(--border));
      background: var(--b, var(--background));
      transition: background 0.3s, border-color 0.3s, box-shadow 0.2s;
    }
    .checkbox-wrapper-13 input[type=checkbox]:after {
      content: "";
      display: block;
      left: 0;
      top: 0;
      position: absolute;
      transition: transform var(--d-t, 0.3s) var(--d-t-e, ease), opacity var(--d-o, 0.2s);
    }
    .checkbox-wrapper-13 input[type=checkbox]:checked {
      --b: var(--active);
      --bc: var(--active);
      --d-o: .3s;
      --d-t: .6s;
      --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled {
      --b: var(--disabled);
      cursor: not-allowed;
      opacity: 0.9;
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled:checked {
      --b: var(--disabled-inner);
      --bc: var(--border);
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled + label {
      cursor: not-allowed;
    }
    .checkbox-wrapper-13 input[type=checkbox]:hover:not(:checked):not(:disabled) {
      --bc: var(--border-hover);
    }
    .checkbox-wrapper-13 input[type=checkbox]:focus {
      box-shadow: 0 0 0 var(--focus);
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch) {
      width: 21px;
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):after {
      opacity: var(--o, 0);
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):checked {
      --o: 1;
    }
    .checkbox-wrapper-13 input[type=checkbox] + label {
      display: inline-block;
      vertical-align: middle;
      cursor: pointer;
      margin-left: 4px;
    }

    .checkbox-wrapper-13 input[type=checkbox]:not(.switch) {
      border-radius: 7px;
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):after {
      width: 5px;
      height: 9px;
      border: 2px solid var(--active-inner);
      border-top: 0;
      border-left: 0;
      left: 7px;
      top: 4px;
      transform: rotate(var(--r, 20deg));
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):checked {
      --r: 43deg;
    }
  }

  .checkbox-wrapper-13 * {
    box-sizing: inherit;
  }
  .checkbox-wrapper-13 *:before,
  .checkbox-wrapper-13 *:after {
    box-sizing: inherit;
  }


  /* body {
  margin:80px;
  text-align:center;
} */

/* Full screen image styles 
=================================== */

.scroll-disabled {
  height: 100%;
  overflow: hidden;
}
.img-full-screen, 
.img-placeholder {
  cursor:pointer;
}
.img-placeholder {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: none;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center center;
  background-color: rgba(255, 255, 255, 0); 
  z-index: 999;
} 
</style>


<!-- Table with stripped rows -->
<table class="table datatable">
<thead>
    <tr>
        <th>#</th>
        <th>Profile Pic</th>
        <th>Name</th>
        <th>Proof Name</th>
        <th>Identity Photo</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(count($list_profiles) > 0)
        @foreach($list_profiles as $keys => $list_prof)
            @php
                if($list_prof->is_identityverification != 'approved')
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
                <td>{{ $list_prof->government_id_name }}</td>
                <td>
                    @if($list_prof->identity_file)
                        <img class="img-full-screen" src="{{ url('/').'/'.$list_prof->identity_file }}" alt="" style="height: 100px; width: 100px;">
                    @else
                        -
                    @endif
                </td>
                <td class="text-capitalize">
                @if($list_prof->is_identityverification == 'approved')
                    <span class="badge bg-{{ $badgeColor }}">{{ $list_prof->is_identityverification }}</span>
                    @elseif($list_prof->is_identityverification == 'rejected')
                    <span class="badge bg-{{ $badgeColor }}">{{ $list_prof->is_identityverification }}</span>
                    @elseif($list_prof->is_identityverification == 'pending')
                    <span class="badge bg-{{ $badgeColor }}">{{ $list_prof->is_identityverification }}</span>
                    @else
                    -
                    @endif
                </td>
                <td>
                    @php
                        $encrypted_id = encrypt($list_prof->id);
                    @endphp
                    <!-- <a href="{{route('profile.edit-profile',['id' => $encrypted_id])}}"><i class="bi bi-pencil-square"></i></a>
                    <a href="#" class="delete-profile" onclick="deleteprofile('{{ $list_prof->id }}')" ><i class="ri-delete-bin-6-fill"></i></a> -->
                    
                    <div class="checkbox-wrapper-13">
                    <input type="checkbox" id="checkboxId" onchange="checkBoxChanged(this,'{{ $list_prof->id }}')" <?php if($list_prof->is_identityverification == 'approved') { ?> checked <?php } ?>>
                    </div>

                    
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

<div class="img-placeholder"></div>
{!! $list_profiles->links('pagination') !!}
<!-- End Table with stripped rows -->

<script>
    // Makes the clicked image full screen (uses a <div> with a background image)
$('.img-full-screen').click(function(){
	
    // Prevents scrolling
      $('body').addClass('scroll-disabled');
    
    // Optional: Enables pinch and zoom
      $('meta[name=viewport]').attr('content','width = device-width, initial-scale = 1.00, minimum-scale = 1.00, maximum-scale = 2.00, user-scalable=yes');
      
    // Get image path source
      let imagePath = $(this).attr('src');
    
    // Set image path source
      $('.img-placeholder').attr('style','background-image: url(' + imagePath + ')');
    
    // Show image
      $('.img-placeholder').fadeIn();
      
  });
  
  $('.img-placeholder').click(function(){
  
    // Enables scrolling again
    $('body').removeClass('scroll-disabled');
  
    // Optional: Disables pinch and zoom
    $('meta[name=viewport]').attr('content','width = device-width, initial-scale = 1.00, minimum-scale = 1.00, maximum-scale = 1.00');
  
    // Hide image
    $('.img-placeholder').fadeOut();
  });
</script>