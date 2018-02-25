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
            <h1 class="page-header">News</h1>
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
                    Add new news
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" action="{{ route('createNew') }}" method="post">
                                <div class="form-group">
                                    <label>New</label>
                                    <input class="form-control" type="text" placeholder="Enter text" name="new"
                                           value="{{ old('new') }}">
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

    @can('listNews',\App\News::class)
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
                        News Controller
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>New</th>
                                    <th>User</th>
                                    <th>Approved</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($news as $new)
                                    <tr align="center" class="item-{{$new->id}}">
                                        <td>{{ $new->new }}</td>
                                        <td>{{ $new->user->name }}</td>
                                        <td>{{ $new->approved ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('listComments',['new' => $new->id]) }}"
                                               class="btn dark btn-outline sbold">Comments</a>

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
                        <form class="form-horizontal news-form" role="form" id="editNewForm" action="#!">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Enter text" name="new"
                                               value="{{ old('new') }}" id="new">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="new_id" id="new_id">
                        </form>
                        <div class="generalContent">
                            Are you sure you want to <span class="name"></span> ?
                            <span class="hidden id"></span>
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
    @endcan

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
            $('#footer_action_button').removeClass('delete-new');
            $('#footer_action_button').removeClass('approve-new');
            $('#footer_action_button').addClass('edit-new');
            $('.modal-title').text('Edit new');
            $('#new_id').val($(this).data('id'));
            $('#new').val($(this).data('new'));
            $('.generalContent').hide();
            $('.news-form').show();
        });

        $('.modal-footer').on('click', '.edit-new', function () {
            var id = $('input[name=new_id]').val();
            var formData = new FormData($('#editNewForm')[0]);

            $.ajax({
                type: 'post',
                url: '/news/' + id + '/edit',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.item-' + id).replaceWith(data);
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text('New updated successfully.');
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


        $(document).on('click', '.delete-modal', function () {
            $('#footer_action_button').text(" Delete");
            $('#footer_action_button').removeClass('btn-success');
            $('#footer_action_button').addClass('btn-danger');
            $('#footer_action_button').removeClass('edit-new');
            $('#footer_action_button').removeClass('approve-new');
            $('#footer_action_button').addClass('delete-new');
            $('.modal-title').text('Delete New');
            $('.id').text($(this).data('id'));
            $('.generalContent').show();
            $('.news-form').hide();
            $('.name').html('delete this new');
        });

        $('.modal-footer').on('click', '.delete-new', function () {
            var id = $('.id').text();
            $.ajax({
                type: 'post',
                url: '/news/' + id + '/delete',
                success: function (data) {
                    $('.item-' + id).remove();
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
            $('#footer_action_button').removeClass('edit-new');
            $('#footer_action_button').removeClass('delete-new');
            $('#footer_action_button').addClass('approve-new');
            $('.modal-title').text('Approve Or Disapprove New');
            $('.id').text($(this).data('id'));
            $('.generalContent').show();
            $('.news-form').hide();
            $('.name').html('(approve or disapprove) this new');
        });

        $('.modal-footer').on('click', '.approve-new', function () {
            var id = $('.id').text();
            $.ajax({
                type: 'post',
                url: '/news/' + id + '/toggle-approval',
                success: function (data) {
                    $('.item-' + id).replaceWith(data);
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