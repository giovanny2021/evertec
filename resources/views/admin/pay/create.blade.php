@extends('layouts.admin')
@section('content')
@section('styles')
    <style>
        .promedio {
            margin-top: 100px;
            margin-left: 360px;
            font-size: 80px;
        }
    </style>
@endsection
<div class="card">
    <div class="card-header">
        Crear Compra
        <div class="box-tools pull-right">
            <a href="{{ url('admin') }}">
                <i class="fa fa-fw fa-reply-all"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <form  action="{{ route('admin.pay') }}"  method="POST" id="compra" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="customer_name">Customer Email</label>
                        <input type="email" name="customer_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="customer_name">Customer Mobile</label>
                        <input type="number" name="customer_mobile" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> Comprar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(document).ready(function(){

    });

</script>
@endsection
@endsection



