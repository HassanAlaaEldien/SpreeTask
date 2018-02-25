<div class="general-content">
    @foreach($comments as $comment)
        <div class="alert alert-info">
            <span>{{ $comment->comment }}</span>
        </div>
    @endforeach
</div>