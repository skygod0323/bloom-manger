@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Settings</h1>
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
            <form method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="token_price">Token Price</label>
                                <input type="number" class="form-control" name="token_price" id="token_price" placeholder="Enter Progress" value="{{ isset($settings['token_price']) ? (float)$settings['token_price'] : ''}}" step="0.001" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wallet_address">Wallet Address</label>
                                <input type="text" class="form-control" name="wallet_address" id="wallet_address" placeholder="Enter Wallet Address" value="{{ isset($settings['wallet_address']) ? $settings['wallet_address'] : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="private_key">Wallet Private Key</label>
                                <input type="text" class="form-control" name="private_key" id="private_key" placeholder="Enter Wallet Private Key" value="{{ isset($settings['private_key']) ? $settings['private_key'] : ''}}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contract_address">Contract Address</label>
                                <input type="text" class="form-control" name="contract_address" id="contract_address" placeholder="Enter Contract Address" value="{{ isset($settings['contract_address']) ? $settings['contract_address'] : ''}}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contract_abi">Contract ABI</label>
                                <textarea class="form-control" name="contract_abi" id="contract_abi" placeholder="Enter Contract ABI" required>{{ isset($settings['contract_abi']) ? $settings['contract_abi'] : ''}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sale_progress">Sale Progress For Current Round (%)</label>
                                <input type="number" class="form-control" name="sale_progress" id="sale_progress" placeholder="Enter Progress" value="{{ isset($settings['sale_progress']) ? (float)$settings['sale_progress'] : ''}}" step="0.01" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function() {
            var settings = @json($settings);
            console.log(settings);
        })
    </script>
@stop