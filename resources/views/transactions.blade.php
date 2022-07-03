@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
    <h1>Transactions</h1>
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
        <div class="box-body">
            <table id="transaction-table" class="table table-bordered">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>User</td>
                        <td>Amount</td>
                        <td>Cost</td>
                        <td>Wallet Address</td>
                        <td>Pay Code</td>
                        <td>Time</td>
                        <td>Payment</td>
                        <td>Sending Status</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@stop

@section('js')
    <script>
        $(function() {
            
            var appUrl = '{{ env('APP_URL') }}';
            var hookURL = '{{ env('HOOK_URL') }}';
            
            var dt;
            var transactions = @json($transactions);

            initDt(transactions);

            function initDt(transactions) {
                if (!dt) {
                    dt = $('#transaction-table').DataTable({
                        'data': transactions,
                        'paging'      : true,
                        'lengthChange': false,
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
                                    var imageUrl = data.user.image ? appUrl + '/' + data.user.image : appUrl + '/img/others/profile.png';
                                    var html = `<div class="user_info">
                                                    <img src="${imageUrl}" />
                                                    <span>${data.user.full_name.length > 10 ? data.user.full_name.substr(0, 10) + '...' : data.user.full_name}</span>
                                                </div>`
                                    return html;
                                }
                            },
                            {   data: 'token_amount' },
                            {   data: 'buy_cost' },
                            {   
                                data: (data) => {
                                    return `<a href="https://bscscan.com/address/${data.wallet_address}" target="_blank" title="${data.wallet_address}">
                                                ${data.wallet_address.length > 10 ? data.wallet_address.substr(0, 10) + '...' : data.wallet_address}
                                            </a>`
                                }
                            },
                            {   
                                data: (data) => {
                                    return `<a href="https://commerce.coinbase.com/charges/${data.payment_code}" target="_blank">
                                                ${data.payment_code}
                                            </a>`
                                }
                            },
                            {   data: 'time' },
                            {   
                                data: (data) => {
                                    if (data.payment_status == 'confirmed') {
                                        var html = `<span class="text-success">Confirmed</span>`    
                                    } else if (data.payment_status == 'pending') {
                                        var html = `<span class="text-warning">Pending</span>`    
                                    } else {
                                        var html = `<span class="text-danger">Cancelled</span>`    
                                    }
                                    return html;
                                }
                            },
                            {   
                                data: (data) => {
                                    if (data.purchase_status == 'success') {
                                        var html = `<span class="text-success">Success</span>`    
                                    } else if (data.purchase_status == 'pending') {
                                        var html = `<span class="text-warning">Pending</span>`    
                                    } else {
                                        var html = `<span class="text-danger">Cancelled</span>`    
                                    }
                                    return html;
                                }
                            },
                            {
                                width: '60px',
                                data: function(data) {
                                    var html = '';

                                    if (data.purchase_status == 'cancelled')
                                        html += '<a class="btn btn-xs btn-success action_btn" data-code="' + data.payment_code + '" style="margin-right:5px">Send</a>';
                                    if (data.tx_hash && data.purchase_status == 'success')
                                        html += '<a class="btn btn-xs btn-info view_sms_btn" target="_blank" href="https://bscscan.com/tx/'
                                             + data.tx_hash + '">View</a>'

                                    return html;
                                }
                            }
                            
                        ]
                    });

                    $('#transaction-table').on('click', 'tbody td .action_btn', function(e) {
                        var ele = e.target;
                        var code = $(ele).data('code');

                        $.ajax({
                            method: 'post',
                            url: hookURL + '/manual_send',
                            data: {
                                code: code
                            },
                            success: (res) => {
                                res = JSON.parse(res);
                                console.log(res);
                                if (res.success) {
                                    alert('Request sent. Wait a moment. Please refresh after few moment. It would take some time.');
                                } else {
                                    alert('Error occured');
                                }
                            },
                            error: (err) => {
                                console.log(err);
                            }
                        })
                    });
                }
            }

         
        })
    </script>
@stop