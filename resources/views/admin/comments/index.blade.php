@extends('layouts.master')

@section('styles')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('assets/css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">comments</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
@endsection

@section('content')

    @include('includes.info-box')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add new comment
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" action="{{ route('createComment',['new' => $new->id]) }}" method="post">
                                <div class="form-group">
                                    <label>Comment</label>
                                    <input class="form-control" type="text" placeholder="Enter text" name="comment"
                                           value="{{ old('comment') }}">
                                </div>
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-default">Add</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>


    <div class="alert alert-danger hidden">
        <span class="general-error"></span>
    </div>

    <div class="alert alert-success hidden">
        <span class="general-success"></span>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Comments Controller
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>Comment</th>
                                <th>New</th>
                                <th>User</th>
                                <th>User Type</th>
                                <th>Approved</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                <tr align="center" class="item-{{$comment->id}}">
                                    <td>{{ $comment->comment }}</td>
                                    <td>{{ $comment->new->new }}</td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>{{ $comment->user->roles->first()->name }}</td>
                                    <td>{{ $comment->approved ? 'Yes' : 'No'}}</td>
                                    <td>
                                        <a data-toggle='modal' data-target='#full'
                                           class="btn dark btn-outline sbold edit-modal"
                                           data-new-id="{{ $comment->new->id }}"
                                           data-id="{{ $comment->id }}"
                                           data-comment="{{ $comment->comment }}">Edit</a>

                                        @if($comment->user->hasRole('User'))
                                            <a data-toggle='modal' data-target='#full'
                                               class="btn dark btn-outline sbold approve-modal"
                                               data-new-id="{{ $comment->new->id }}"
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
                                           data-new-id="{{ $comment->new->id }}"
                                           data-id="{{ $comment->id }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- End: life time stats -->

    <!-- Modal -->
    <div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modal Title</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal comment-form" role="form" id="editCommentForm" action="#!">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Comment</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Enter text" name="comment"
                                           value="{{ old('comment') }}" id="comment">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="new_id" id="new_id">
                        <input type="hidden" name="comment_id" id="comment_id">
                    </form>
                    <div class="generalContent">
                        Are you sure you want to <span class="name"></span> ?
                        <span class="hidden id"></span>
                        <span class="hidden new-id"></span>
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

        $(document).on('click', '.edit-modal', function () {
            $('#footer_action_button').text(" Save");
            $('#footer_action_button').removeClass('btn-danger');
            $('#footer_action_button').addClass('btn-success');
            $('#footer_action_button').removeClass('delete-comment');
            $('#footer_action_button').removeClass('approve-comment');
            $('#footer_action_button').addClass('edit-comment');
            $('.modal-title').text('Edit comment');
            $('#new_id').val($(this).data('new-id'));
            $('#comment_id').val($(this).data('id'));
            $('#comment').val($(this).data('comment'));
            $('.generalContent').hide();
            $('.comment-form').show();
        });

        $('.modal-footer').on('click', '.edit-comment', function () {
            var new_id = $('input[name=new_id]').val();
            var comment_id = $('input[name=comment_id]').val();
            var formData = new FormData($('#editCommentForm')[0]);

            $.ajax({
                type: 'post',
                url: '/news/' + new_id + '/comments/' + comment_id + '/edit',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.item-' + comment_id).replaceWith(data);
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text('Comment updated successfully.');
                    setTimeout(function () {
                        $('.alert-success').addClass('hidden');
                        $('.general-success').text('');
                    }, 3000);
                },
                error: function (data) {
                    if (data.responseJSON.errors.hasOwnProperty('comment')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.comment[0]);
                    }
                }
            });
        });


        $(document).on('click', '.delete-modal', function () {
            $('#footer_action_button').text(" Delete");
            $('#footer_action_button').removeClass('btn-success');
            $('#footer_action_button').addClass('btn-danger');
            $('#footer_action_button').removeClass('edit-comment');
            $('#footer_action_button').removeClass('approve-comment');
            $('#footer_action_button').addClass('delete-comment');
            $('.modal-title').text('Delete comment');
            $('.id').text($(this).data('id'));
            $('.new-id').text($(this).data('new-id'));
            $('.generalContent').show();
            $('.comment-form').hide();
            $('.name').html('delete this comment');
        });

        $('.modal-footer').on('click', '.delete-comment', function () {
            var new_id = $('.new-id').text();
            var comment_id = $('.id').text();

            $.ajax({
                type: 'post',
                url: '/news/' + new_id + '/comments/' + comment_id + '/delete',
                success: function (data) {
                    $('.item-' + comment_id).remove();
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text(data.success);
                    setTimeout(function () {
                        $('.alert-success').addClass('hidden');
                        $('.general-success').text('');
                    }, 3000);
                }
            });
        });


        $(document).on('click', '.approve-modal', function () {
            $('#footer_action_button').text(" Save");
            $('#footer_action_button').addClass('btn-success');
            $('#footer_action_button').removeClass('btn-danger');
            $('#footer_action_button').removeClass('edit-comment');
            $('#footer_action_button').removeClass('delete-comment');
            $('#footer_action_button').addClass('approve-comment');
            $('.modal-title').text('Approve Or Disapprove comment');
            $('.id').text($(this).data('id'));
            $('.new-id').text($(this).data('new-id'));
            $('.generalContent').show();
            $('.comment-form').hide();
            $('.name').html('(approve or disapprove) this comment');
        });

        $('.modal-footer').on('click', '.approve-comment', function () {
            var new_id = $('.new-id').text();
            var comment_id = $('.id').text();

            $.ajax({
                type: 'post',
                url: '/news/' + new_id + '/comments/' + comment_id + '/toggle-approval',
                success: function (data) {
                    $('.item-' + comment_id).replaceWith(data);
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text('Operation completed successfully.');
                    setTimeout(function () {
                        $('.alert-success').addClass('hidden');
                        $('.general-success').text('');
                    }, 3000);
                }
            });
        });

    </script>


    <!-- DataTables JavaScript -->
    <script src="{{ asset('assets/js/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/dataTables.bootstrap.min.js') }}"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>

@endsection