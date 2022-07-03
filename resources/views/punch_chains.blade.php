@extends('adminlte::page')

@section('title', 'Punch Chain')

@section('content_header')
    <h1>Punch Chains</h1>
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

        .punch_blocks {

        }

        .punch_blocks .item_margin {
            margin-left: 5px;
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
                        <td>Punch Chain</td>
                        <td>Total Buy Amount</td>
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
            
            var dt;
            var punchs = @json($punchs);
            
            var punch_chains = [];
            for (const [key, value] of Object.entries(punchs)) {
                punch_chains.push(value);
            }

            console.log(punch_chains);

            initDt(punch_chains);

            function initDt(punch_chains) {
                if (!dt) {
                    dt = $('#transaction-table').DataTable({
                        'data': punch_chains,
                        'paging'      : true,
                        'lengthChange': false,
                        'ordering'    : true,
                        'info'        : true,
                        'autoWidth'   : false,
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
                                                    <span>${data.user.full_name}</span>
                                                </div>`
                                    return html;
                                }
                            },
                            {   
                                data: (data) => {

                                    var html = '<div class="punch_blocks">';

                                    data.punches.forEach((item, key) => {

                                        let itemEl;
                                        if (key == 0) {
                                            itemEl = "<a class='' target='_blank' href='https://bscscan.com/tx/" + item.tx_hash + "'>#" + item.block + "</a>"
                                        } else {
                                            html += ', ';
                                            itemEl = "<a class='item_margin' target='_blank' href='https://bscscan.com/tx/" + item.tx_hash + "'>#" + item.block + "</a>"
                                        }
                                        
                                        html += itemEl;
                                        
                                    })

                                    html += '</div>';

                                    return html;
                                    
                                }
                            },
                            {   data: 'user.total' },
                        ]
                    });
                }
            }

         
        })
    </script>
@stop