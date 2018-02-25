<tr align="center" class="item-{{$user->id}}">
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->roles->first()->name }}</td>
    <td>
        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold edit-info-modal"
           data-id="{{ $user->id }}"
           data-name="{{ $user->name }}" data-email="{{ $user->email }}"
           data-role="{{ $user->roles->first()->id }}">Edit Info</a>

        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold edit-password-modal"
           data-id="{{ $user->id }}">Edit password</a>

        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold delete-modal"
           data-id="{{ $user->id }}">Delete</a>
    </td>
</tr>