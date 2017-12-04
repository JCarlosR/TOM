@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones finalizadas</div>

            <div class="panel-body">
                <p>Mostrando publicaciones que ya han finalizado, empezando por las más recientes.</p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Grupo destino</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Estado</th>
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
                        <td>{{ $post->description }}</td>
                        <td>
                            <span class="label label-default">{{ $post->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $scheduled_posts->links() }}

                <a href="{{ url('/facebook/posts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-step-backward"></i> Volver al listado de publicaciones programadas
                </a>
            </div>
        </div>
    </div>
@endsection
