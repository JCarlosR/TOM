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

                <p>Listado de usuarios que han participado en sus promociones.</p>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>E-mail</th>
                        <th>Facebook</th>
                        <th>Resultado</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($participations as $participation)
                        <tr>
                            <td>{{ $participation->user_id }}</td>
                            <td>{{ $participation->user->name }}</td>
                            <td>{{ $participation->user->email }}</td>
                            <td>
                                <a href="//fb.com/{{ $participation->user->facebook_user_id }}" target="_blank">
                                    Visitar facebook
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
