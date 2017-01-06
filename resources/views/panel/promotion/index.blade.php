@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Administrar fan page</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Listado de promociones relacionadas con la fanpage.</p>
                <div class="well bs-component">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            @include('includes.fan_page_data')
                        </div>

                        <div class="col-md-6">
                            <h2>Promociones</h2>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($promotions as $promotion)
                                    <tr>
                                        <td>{{ $promotion->description }}</td>
                                        <td>
                                            <a href="{{ url("config/promotion/$promotion->id/edit") }}" class="btn btn-primary btn-sm" title="Editar promoción">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>

                                            <a href="{{ url("config/promotion/$promotion->id/excel") }}" class="btn btn-success btn-sm" title="Exportar a Excel">
                                                <span class="fa fa-file-excel-o"></span>
                                            </a>

                                            <a href="{{ url("/promotion/$promotion->id/instructions") }}" class="btn btn-info btn-sm" title="Link para añadir a facebook">
                                                <span class="fa fa-link"></span>
                                            </a>

                                            <a href="{{ url("config/promotion/$promotion->id/delete") }}" class="btn btn-danger btn-sm" title="Dar de baja" onclick="return confirmDelete();">
                                                <span class="fa fa-remove"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('¿Está seguro que desea eliminar esta promoción?');
        }
    </script>
@endsection