@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('css')
    <style>
        .user_info img{
            width: 40px;
            height: 40px;
            border-radius: 100%;
            margin-right: 10px
        }

        tbody td {
            line-height: 40px!important;
        }
        
    </style>
@stop


@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Users</h3>
        </div>
        <div class="box-body">
            <table id="users-table" class="table table-bordered">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Default Wallet</td>
                        <td>Login Type</td>
                        <td>Last Login</td>
                        <!-- <td>Action</td> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            
            var appUrl = '{{ env('APP_URL') }}';
            
            var dt;
            var users = @json($users);
            console.log(users);

            initDt(users);

            function initDt(users) {
                if (!dt) {
                    dt = $('#users-table').DataTable({
                        'data': users,
                        'paging'      : true,
                        'lengthChange': false,
                        // 'searching'   : false,
                        'ordering'    : true,
                        'info'        : true,
                        'autoWidth'   : false,
                        // "order": [[ 0, "desc" ]],
                        "columns": [ 
                            {
                                width: '20px',
                                render: function ( data, type, row, meta ) {
                                    return meta.row + 1;
                                },
                            },
                            {   
                                data: (data) => {
                                    var imageUrl = data.image ? appUrl + '/' + data.image : appUrl + '/img/others/profile.png';
                                    var html = `<div class="user_info">
                                                    <img src="${imageUrl}" />
                                                    <span>${data.full_name}</span>
                                                </div>`
                                    return html;
                                }
                            },
                            {   data: 'email_address' },
                            {   data: 'default_wallet' },
                            {   data: data => data.google_id ? "Google Login" : "Email Login"},
                            {   data: 'last_login_time', defaultContent: '' },
                            // {
                            //     width: '60px',
                            //     data: function(data) {
                            //         var html = '<a class="btn btn-xs btn-success action_btn" data-userId="'
                            //                  + data.userId + '" data-notificationid="' + data.notificationId + '">Send</a>'
                            //                  + '<a class="btn btn-xs btn-info view_sms_btn" style="margin-left:5px" data-userId="' + data.userId + '">View</a>'

                            //         return html;
                            //     }
                            // }
                        ]
                    });
                }
            }

            // $('#users-table').on('click', 'tbody td .action_btn', function(e) {
            //     var ele = e.target;
            //     var userId = $(ele).data('userid');
            //     var notificationId = $(ele).data('notificationid');

            //     $('#sms_modal').modal('show');
            //     $('#sms_modal #modal_sms_text').val('');
            //     $('#sms_modal #modal_phone').val('');
            //     $('#sms_modal #modal_userid').val(userId);
            //     $('#sms_modal #modal_notificationid').val(notificationId);
            // });
        })
    </script>
@stop