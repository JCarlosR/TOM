@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
@endsection

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Listado de potenciales clientes</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p><strong>Felicidades por tus posibles clientes!</strong></p>
                <p>Ahora dales seguimiento hasta conseguir la venta.</p>

                <p class="text-muted">Listado de usuarios que han participado en tus promociones.</p>
                <table class="table table-bordered table-hover" id="clients-table">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>E-mail</th>
                        <th>Ubicación</th>
                        <th>Fanpage</th>
                        <th>Promoción</th>
                        <th>Resultado</th>
                        <th>Fecha</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($participations as $participation)
                        <tr>
                            <td>
                                <a href="//fb.com/{{ $participation->user->facebook_user_id }}" target="_blank">
                                    {{ $participation->user->name }}
                                </a>
                            </td>
                            <td>{{ $participation->user->email }}</td>
                            <td>{{ $participation->user->location_name }}</td>
                            <td>
                                <a href="//fb.com/{{ $participation->promotion->fanPage->fan_page_id }}" target="_blank" title="Fanpage que capturó el lead">
                                    Visitar
                                </a>
                            </td>
                            <td>
                                <a href="{{ $participation->promotion->fullLink }}" target="_blank" title="Promoción en la que participó">
                                    {{ $participation->promotion->description }}
                                </a>
                            </td>
                            <td>{{ $participation->is_winner ? 'Ganó' : 'Perdió' }}</td>
                            <td>{{ $participation->created_at }}</td>
                            <td>
                                <select class="form-control">
                                    <option value="A contactar">A contactar</option>
                                    <option value="En progreso">En progreso</option>
                                    <option value="Con venta">Con venta</option>
                                    <option value="Sin venta">Sin venta</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#clients-table').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { targets: [4, 5], className: 'none' }
                ]
            });
        });
    </script>
@endsection
