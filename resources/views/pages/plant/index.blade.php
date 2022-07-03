@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Plants</h1>
@stop

@section('css')
    <style>
        .table-plants {
            width: 100%;
        }

        .table-plants td {
            padding: 5px 10px;
            border: 1px solid #d2d6de;
            height: 45px;
        }

        .table-plants thead td {
            font-weight: bold;
        }

        .btn-wrapper {
            margin-top: 10px;
        }

        .error {
            background-color: rgb(254 226 226);
            border: 1px solid rgb(248 113 113);
            border-radius: 0.25rem;
            padding: 0.75rem 1rem;
            margin-bottom: 10px;
        }

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <form action="{{ route('save_plant') }}" method="post">
                @csrf
                @if (isset($error))
                    <div class="error">
                        <span class="error-text">{{ $error }}</span>
                    </div>
                @endif
                <table class="table-plants">
                    <thead>
                        <td style="width: 10%">
                            <input type="checkbox" name="remember" id="check_all"> 
                            <!-- <label for="check_all">Select All</label> -->
                        </td>
                        <td style="width: 20%">Name</td>
                        <td style="width: 25%">Quantity</td>
                        <td style="width: 25%">Harvest</td>
                        <td style="width: 20%">Spacing</td>
                    </thead>
                    <tbody>
                        @foreach ($plants as $index => $plant)
                            @if ($plant->has_task === 0)
                                <tr>
                                    <td>
                                        <input type="hidden" value="{{$plant->id}}" name="plants[{{$index}}][id]">
                                        <input type="checkbox" name="plants[{{$index}}][selected]" class="checkbox">
                                    </td>
                                    <td>{{ $plant->name }}</td>
                                    <td>
                                        <input class="form-control" type="number" name="plants[{{$index}}][quantity]">
                                    </td>
                                    <td>
                                        <select class="form-control" name="plants[{{$index}}][harvest]">
                                            <option value="Early">Early</option>
                                            <option value="Mid">Mid</option>
                                            <option value="Late">Late</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span style="margin-right: 10px; vertical-align: inherit">Stagger?</span>
                                        <input type="checkbox" name="plants[{{$index}}][stagger]" class="stagger">
                                        <div class="stagger-inputs" style="display: none">
                                            <input class="form-control" type="number" name="plants[{{$index}}][plant_count]" placeholder="# of plantings">
                                            <input class="form-control" type="number" name="plants[{{$index}}][plant_days]" placeholder="# of days between" style="margin-top: 5px">
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td><input type="checkbox" readonly checked disabled></td>
                                    <td>{{ $plant->name }}</td>
                                    <td>{{ $plant->quantity }}</td>
                                    <td>{{ $plant->harvest }}</td>
                                    <td>
                                        @if ($plant->stagger === 'yes')
                                            <span class="text-success text-bold">{{ $plant->stagger }}</span>
                                            <div>{{ $plant->plant_count }} of plants</div>
                                            <div>{{ $plant->plant_days }} of days between</div>
                                        @else
                                            <span class="text-danger text-bold">{{ $plant->stagger }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="btn-wrapper">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-primary" href="{{ route('plant_setting') }}">Add Custom Plant</a>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function() {
            $('#check_all').click(function() {
                if(this.checked) {
                    $(".checkbox").prop("checked", true);
                } else {
                    $(".checkbox").prop("checked", false);
                }
            })

            $(".checkbox").click(function() {
                let checked = true;
                const checkboxs = $('.checkbox');
                for (let i=0; i < checkboxs.length; i++) {
                    if (!checkboxs[i].checked) checked = false;
                }

                console.log(checked);

                if(checked) {
                    $("#check_all").prop("checked", true);
                } else {
                    $("#check_all").prop("checked", false);
                }
            })

            $(".stagger").click(function() {
                if(this.checked) {
                    $(this).parent().find('.stagger-inputs').show();
                    // $(this).parent().find('.stagger-inputs input').prop('required', true);
                } else {
                    $(this).parent().find('.stagger-inputs').hide();
                    // $(this).parent().find('.stagger-inputs input').prop('required', false);
                }
            })
        })
    </script>
@stop