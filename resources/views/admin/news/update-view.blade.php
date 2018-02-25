
<tr align="center" class="item-{{$new->id}}">
    <td>{{ $new->new }}</td>
    <td>{{ $new->user->name }}</td>
    <td>{{ $new->approved ? 'Yes' : 'No' }}</td>
    <td>
        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold edit-modal"
           data-id="{{ $new->id }}"
           data-new="{{ $new->new }}">Edit</a>

        @if($new->user->hasRole('User'))
            <a data-toggle='modal' data-target='#full'
               class="btn dark btn-outline sbold approve-modal"
               data-id="{{ $new->id }}">
                @if($new->approved)
                    Disapprove
                @else
                    Approve
                @endif
            </a>
        @endif

        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold delete-modal"
           data-id="{{ $new->id }}">Delete</a>
    </td>
</tr>