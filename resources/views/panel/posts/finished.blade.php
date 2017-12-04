@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones finalizadas</div>

            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Grupo destino</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($scheduled_posts as $post)
                    <tr>
                        <td>
                            <a href="//fb.com/{{ $post->fb_destination_id }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </td>
                        <td>{{ $post->scheduled_date }}</td>
                        <td>{{ $post->scheduled_time }}</td>
                        <td>{{ $post->type }}</td>
                        <td>
                            <span class="label label-default">{{ $post->status }}</span>
                        </td>
                        <td>
                            <a href="" class="btn btn-primary btn-sm" disabled>
                                Ver contenido
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <a href="{{ url('/facebook/posts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-step-backward"></i> Volver al listado de publicaciones programadas
                </a>
            </div>
        </div>
    </div>
@endsection
