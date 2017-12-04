@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicar en grupos de <i class="fa fa-facebook-square"></i></div>

            <div class="panel-body">
                @if (session('notification'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('notification') }}</p>
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($availablePermissions)
                    <p>Enhorabuena! TOM dispone de un token para publicar a tu nombre.</p>
                    <p class="small">Última fecha de actualización del token: {{ auth()->user()->fb_access_token_updated_at ?: 'Nunca' }}</p>
                    <p class="small">Te recomendamos actualizar este token una vez por mes. Su duración promedio es de 2 meses.</p>
                    <a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-key"></i> Solicitar nuevo token
                    </a>
                @else
                    <p>Antes de programar una publicación, por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>
                    <a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-key"></i> Otorgar permisos a TOM
                    </a>
                @endif

                <hr>
                <p>¿Deseas programar una nueva publicación?</p>
                <a href="{{ url('/facebook/posts/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                    Registrar nueva publicación
                </a>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones programadas</div>

            <div class="panel-body">
                <p>Actualmente tienes programadas {{ $scheduled_posts->count() }} publicaciones.</p>
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
                            <a href="{{ url('facebook/posts/delete?post_id='.$post->id) }}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Está seguro que desea eliminar esta publicación?')">
                                Cancelar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <a href="{{ url('/facebook/posts/finished') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-list"></i> Ver publicaciones finalizadas
                </a>
            </div>
        </div>
    </div>
@endsection
