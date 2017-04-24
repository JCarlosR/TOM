@extends('layouts.dashboard')

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
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>E-mail</th>
                        <th>Ubicación</th>
                        <th>Fanpage</th>
                        <th>Promoción</th>
                        <th>Resultado</th>
                        <th>Fecha</th>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
