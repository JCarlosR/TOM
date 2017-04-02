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
                <div class="well bs-component">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>E-mail</th>
                            <th>Facebook</th>
                            <th>Resultado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($participants as $participant)
                            <tr>
                                <td>{{ $participant->user_id }}</td>
                                <td>{{ $participant->user->name }}</td>
                                <td>{{ $participant->user->email }}</td>
                                <td>{{ $participant->user->facebook_user_id }}</td>
                                <td>{{ $participant->is_winner ? 'Ganó' : 'Perdió' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
