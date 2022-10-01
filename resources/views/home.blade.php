@extends('layouts.admin')
@section('content')

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong>Success! </strong> {{ session('success') }}
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong>Error !</strong> {{ session('error') }}
    </div>
@endif

@can('pay_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.pay.create") }}">
                Agregar Compra
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
       Listado de Pedidos
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="pay" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">
                            N°
                        </th>
                        <th>
                           Customer Name
                        </th>
                        <th>
                           Customer Email
                        </th>
                        <th>
                           Customer Mobile
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Fecha de Creacion
                        </th>
                        <th>
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(document).ready(function(){

        $('#pay').DataTable({
            "serverSide": true,
            "ajax": "{{ url('admin/payDatatable') }}",
            "columns": [
                {data: 'id'},
                {data: 'customer_name'},
                {data: 'customer_email'},
                {data: 'customer_mobile'},
                {data: 'status'},
                {data: 'created_at'},
                {data: 'btn'},
            ]

        });
    });

</script>
@endsection
@endsection
