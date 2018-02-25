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
            <h1 class="page-header">Users</h1>
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
                    Add new user
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" action="{{ route('createUser') }}" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" placeholder="Enter text" name="name"
                                           value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" placeholder="Enter text" type="email" name="email"
                                           value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" placeholder="Enter text" type="password"
                                           name="password">
                                </div>
                                <div class="form-group">
                                    <label>Password Confirmation</label>
                                    <input class="form-control" placeholder="Enter text" type="password"
                                           name="password_confirmation">
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" name="role">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
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
                    Users Controller
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
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
                    <form class="form-horizontal info-form" role="form" id="editUserForm" action="#!">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Username</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Enter text" name="name"
                                           value="{{ old('name') }}" id="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input class="form-control" placeholder="Enter text" type="email" name="email"
                                           value="{{ old('email') }}" id="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Role</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="role" id="role">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="user_id" id="user_id">
                    </form>
                    <form class="form-horizontal password-form" role="form" id="editUserPasswordForm" action="#!">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Old password</label>
                                <div class="col-md-9">
                                    <input class="form-control" placeholder="Enter text" type="password"
                                           name="old_password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                    <input class="form-control" placeholder="Enter text" type="password"
                                           name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password Confirmation</label>
                                <div class="col-md-9">
                                    <input class="form-control" placeholder="Enter text" type="password"
                                           name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="user_password_id" id="user_password_id">
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

@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name = "csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.edit-info-modal', function () {
            $('#footer_action_button').text(" Save");
            $('#footer_action_button').removeClass('btn-danger');
            $('#footer_action_button').addClass('btn-success');
            $('#footer_action_button').removeClass('delete-user');
            $('#footer_action_button').removeClass('edit-user-password');
            $('#footer_action_button').addClass('edit-user');
            $('.modal-title').text('Edit user');
            $('#user_id').val($(this).data('id'));
            $('#name').val($(this).data('name'));
            $('#email').val($(this).data('email'));
            $('#role').val($(this).data('role'));
            $('.generalContent').hide();
            $('.password-form').hide();
            $('.info-form').show();
        });

        $('.modal-footer').on('click', '.edit-user', function () {
            var id = $('input[name=user_id]').val();
            var formData = new FormData($('#editUserForm')[0]);

            $.ajax({
                type: 'post',
                url: '/users/' + id + '/edit',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.item-' + id).replaceWith(data);
                    $('.alert-danger').addClass('hidden');
                    $('.alert-success').removeClass('hidden');
                    $('.general-success').text('User info updated successfully.');
                    setTimeout(function () {
                        $('.alert-success').addClass('hidden');
                        $('.general-success').text('');
                    }, 3000);
                },
                error: function (data) {
                    if (data.responseJSON.errors.hasOwnProperty('name')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.name[0]);
                    }
                    if (data.responseJSON.errors.hasOwnProperty('email')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.email[0]);
                    }
                    if (data.responseJSON.errors.hasOwnProperty('role')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.role[0]);
                    }
                }
            });
        });


        $(document).on('click', '.edit-password-modal', function () {
            $('#footer_action_button').text(" Save");
            $('#footer_action_button').removeClass('btn-danger');
            $('#footer_action_button').addClass('btn-success');
            $('#footer_action_button').removeClass('delete-user');
            $('#footer_action_button').removeClass('edit-user');
            $('#footer_action_button').addClass('edit-user-password');
            $('.modal-title').text('Edit user password');
            $('#user_password_id').val($(this).data('id'));
            $('.generalContent').hide();
            $('.info-form').hide();
            $('.password-form').show();
        });

        $('.modal-footer').on('click', '.edit-user-password', function () {
            var id = $('input[name=user_password_id]').val();
            var formData = new FormData($('#editUserPasswordForm')[0]);

            $.ajax({
                type: 'post',
                url: '/users/' + id + '/edit-password',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.success) {
                        $('.alert-danger').addClass('hidden');
                        $('.alert-success').removeClass('hidden');
                        $('.general-success').text(data.success);
                        setTimeout(function () {
                            $('.alert-success').addClass('hidden');
                            $('.general-success').text('');
                        }, 3000);
                    } else {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.fail);
                    }
                },
                error: function (data) {
                    if (data.responseJSON.errors.hasOwnProperty('old_password')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.old_password[0]);
                    }
                    if (data.responseJSON.errors.hasOwnProperty('password')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.password[0]);
                    }
                    if (data.responseJSON.errors.hasOwnProperty('password_confirmation')) {
                        $('.alert-success').addClass('hidden');
                        $('.alert-danger').removeClass('hidden');
                        $('.general-error').text(data.responseJSON.errors.password_confirmation[0]);
                    }
                }
            });
        });

        $(document).on('click', '.delete-modal', function () {
            $('#footer_action_button').text(" Delete");
            $('#footer_action_button').removeClass('btn-success');
            $('#footer_action_button').addClass('btn-danger');
            $('#footer_action_button').removeClass('edit-user');
            $('#footer_action_button').removeClass('edit-user-password');
            $('#footer_action_button').addClass('delete-user');
            $('.modal-title').text('Delete User');
            $('.id').text($(this).data('id'));
            $('.generalContent').show();
            $('.info-form').hide();
            $('.password-form').hide();
            $('.name').html('delete this user');
        });

        $('.modal-footer').on('click', '.delete-user', function () {
            var id = $('.id').text();
            $.ajax({
                type: 'post',
                url: '/users/' + id + '/delete',
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