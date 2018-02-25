<tr align="center" class="item-{{$comment->id}}">
    <td>{{ $comment->comment }}</td>
    <td>{{ $comment->new->new }}</td>
    <td>{{ $comment->user->name }}</td>
    <td>{{ $comment->user->roles->first()->name }}</td>
    <td>{{ $comment->approved ? 'Yes' : 'No'}}</td>
    <td>
        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold edit-modal"
           data-id="{{ $comment->id }}">Edit</a>

        @if($comment->user->hasRole('User'))
            <a data-toggle='modal' data-target='#full'
               class="btn dark btn-outline sbold approve-modal"
               data-id="{{ $comment->id }}">
                @if($comment->approved)
                    Disapprove
                @else
                    Approve
                @endif
            </a>
        @endif

        <a data-toggle='modal' data-target='#full'
           class="btn dark btn-outline sbold delete-modal"
           data-id="{{ $comment->id }}">Delete</a>
    </td>
</tr>