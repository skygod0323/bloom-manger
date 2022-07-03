@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Plant Settings</h1>
@stop

@section('css')
    <style>
        textarea.form-control {
            height: 120px;
        }
    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <table id="plants-table" class="table table-bordered">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Name</td>
                        <td>Earliest Seed</td>
                        <td>Latest Seed</td>
                        <td>Harden</td>
                        <td>Transplant</td>
                        <td>Maturity</td>
                        <td>Light</td>
                        <td>Depth</td>
                        <td>Seed Note</td>
                        <td>Transplant Note</td>
                        <td>Harvest Note</td>
                        <td>Direct Sow</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
        <form method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="earliest_seed">Earliest Seed</label>
                                <input type="number" class="form-control" name="earliest_seed" id="earliest_seed" placeholder="Enter Earliest Seed" step="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latest_seed">Latest Seed</label>
                                <input type="number" class="form-control" name="latest_seed" id="latest_seed" placeholder="Enter Latest Seed" step="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harden">Harden</label>
                                <input type="number" class="form-control" name="harden" id="harden" placeholder="Enter Harden" step="1" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transplant">Transplant</label>
                                <input type="number" class="form-control" name="transplant" id="transplant" placeholder="Enter Transplant" step="1" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="maturity">Maturity</label>
                                <input type="number" class="form-control" name="maturity" id="maturity" placeholder="Enter Maturity" step="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="light">Light</label>
                                <select name="light" id="light" class="form-control">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="depth">Depth</label>
                                <input type="number" class="form-control" name="depth" id="depth" placeholder="Enter Depth" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="direct_sow">Direct Sow</label>
                                <select name="direct_sow" id="direct_sow" class="form-control">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seed_note">Seed Note</label>
                                <textarea class="form-control" name="seed_note" id="seed_note" placeholder=""></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transplant_note">Transplant Note</label>
                                <textarea class="form-control" name="transplant_note" id="transplant_note" placeholder=""></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harvest_note">Harvest Note</label>
                                <textarea class="form-control" name="harvest_note" id="harvest_note" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Add Plant</button>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function() {

            var toastOption = {
                position_class: "toast-top-right",
                timeout: 300000,
                has_icon: false,
            }

            var dt;

            var plants = @json($plants);
            console.log('plants: ', plants);
            
            // var plants = [
            //     {
            //         name: 'Zinnia',
            //         earliest_seed: 4,
            //         latest_seed: 6,
            //         harden: 1,
            //         transplant: -1,
            //         maturity: 90,
            //         light: 'yes',
            //         depth: 3,
            //         seed_note: 'Bottom Water',
            //         transplant_note: '',
            //         harvest_note: 'Wait until flower is fully open and stem is firm. Frequent harvesting increases yield.',
            //         direct_sow: 'yes'
            //     }
            // ];
            initDt(plants);

            function initDt(plants) {
                if (!dt) {
                    dt = $('#plants-table').DataTable({
                        'data': plants,
                        'paging'      : false,
                        'lengthChange': false,
                        'ordering'    : true,
                        'info'        : false,
                        'autoWidth'   : false,
                        'filter'      : false,
                        "columns": [ 
                            {
                                width: '20px',
                                render: function ( data, type, row, meta ) {
                                    return meta.row + 1;
                                },
                            },
                            {   data: 'name' },
                            {   data: 'earliest_seed' },
                            {   data: 'latest_seed' },
                            {   data: 'harden' },
                            {   data: 'transplant' },
                            {   data: 'maturity' },
                            {   data: 'light' },
                            {   data: 'depth' },
                            {   data: 'seed_note' },
                            {   data: 'transplant_note' },
                            {   data: 'harvest_note' },
                            {   data: 'direct_sow' },
                        ]
                    });
                }
            }


         
        })
    </script>
@stop