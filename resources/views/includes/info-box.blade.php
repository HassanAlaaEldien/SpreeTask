@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger" style="margin: 15px;">
            {{ $error }}
        </div>
    @endforeach
@elseif(Session::has('fail'))
    <div class="alert alert-danger" style="margin: 15px;">
        {{ Session::get('fail') }}
    </div>
@elseif(Session::has('success'))
    <div class="alert alert-success" style="margin: 15px;">
        {{ Session::get('success') }}
    </div>
@endif