@extends('layouts.master')

@section('page-header')
    <div class="row">
        <div class="col-lg-12">
            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('User'))
                <h1 class="page-header">Latest Approved News</h1>
            @else
                <h1 class="page-header">Dashboard</h1>
            @endif
        </div>
        <!-- /.col-lg-12 -->
    </div>
@endsection

@section('content')

    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('User'))

        <div class="alert alert-danger hidden">
            <span class="general-error"></span>
        </div>

        <div class="alert alert-success hidden">
            <span class="general-success"></span>
        </div>

        <div class="row">
            @foreach($news as $new)
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{ $new->user->name }}
                        </div>
                        <div class="panel-body">
                            <p>{{ $new->new }}</p>
                        </div>
                        <div class="panel-footer">
                            {{ $new->created_at->diffForHumans() }}

                            <a data-toggle='modal' data-target='#full'
                               class="btn dark btn-outline sbold add-comment-modal"
                               data-id="{{ $new->id }}"><i class="fa fa-comment"></i> add</a>

                            <a data-toggle='modal' data-target='#full'
                               class="btn dark btn-outline sbold list-comments-modal"
                               data-id="{{ $new->id }}"><i class="fa fa-comment"></i> list</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <P align="center"> Welcome To Spree Task Dashboard</P>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modal Title</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal comment-form" role="form" id="addCommentForm" action="#!">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Comment</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Enter comment" name="comment"
                                           value="{{ old('comment') }}" id="comment">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="new_id" id="new_id">
                    </form>
                    <div class="general-content">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline"
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" id="footer_action_button"
                            class="btn green" data-dismiss="modal"></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name = "csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.add-comment-modal', function () {
            $('#footer_action_button').text(" add");
            $('#footer_action_button').removeClass("hidden");
            $('#footer_action_button').removeClass('btn-danger');
            $('#footer_action_button').addClass('btn-success');
            $('#footer_action_button').addClass('add-comment');
            $('.modal-title').text('Add comment');
            $('#new_id').val($(this).data('id'));
            $('.comment-form').show();
            $('.general-content').hide();
        });

        $('.modal-footer').on('click', '.add-comment', function () {
            var id = $('input[name=new_id]').val();
            var formData = new FormData($('#addCommentForm')[0]);

            $.ajax({
                type: 'post',
                url: '/news/' + id + '/comments/add',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text('Comment added successfully.');
                    setTimeout(function () {
                        $('.alert-success').addClass('hidden');
                        $('.general-success').text('');
                    }, 3000);
                },
                error: function (data) {
                    if (data.responseJSON.errors.hasOwnProperty('new')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.new[0]);
                    }
                }
            });
        });

        $(document).on('click', '.list-comments-modal', function () {
            $('#footer_action_button').addClass("hidden");
            $('#footer_action_button').removeClass('add-comment');
            $('.modal-title').text('List comments');
            $('.comment-form').hide();
            $('.general-content').show();

            var id = $(this).data('id');
            $.get('/news/' + id + '/comments/get-approved-comments', function (data) {
                $('.general-content').replaceWith(data);
            });
        });

    </script>

@endsection