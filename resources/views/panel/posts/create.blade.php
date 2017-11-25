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

                    <hr>

                    <form action="" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="type">Tipo de publicación</label>
                            <select name="type" id="type" required class="form-control">
                                <option value="">Seleccione un tipo de publicación</option>
                                <option value="link">Publicar un enlace</option>
                                <option value="image">Publicar una imagen</option>
                                <option value="video">Publicar un video</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group" id="link_container" style="display: none;">
                            <label for="link">Enlace</label>
                            <input type="url" name="link" id="link" class="form-control">
                        </div>
                        <div class="form-group" id="image_url_container" style="display: none;">
                            <label for="image_url">URL de la imagen</label>
                            <input type="url" name="image_url" id="image_url" class="form-control">
                        </div>
                        <div class="form-group" id="video_url_container" style="display: none;">
                            <label for="video_url">URL del video</label>
                            <input type="url" name="video_url" id="video_url" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="scheduled_date">¿En qué fecha se publicará?</label>
                            <input type="date" class="form-control" name="scheduled_date" required value="{{ old('scheduled_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="scheduled_time">¿A qué hora se publicará?</label>
                            <input type="time" class="form-control" name="scheduled_time" required value="{{ old('scheduled_time') }}">
                        </div>
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-calendar"></i> Programar publicación
                        </button>
                    </form>
                @else
                    <p>Antes de programar una publicación, por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>
                    <a href="{{url('/facebook/publish-group-permissions')}}" class="btn btn-primary btn-sm">Otorgar permisos a TOM</a>
                @endif
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones programadas</div>

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
                            <a href="" class="btn btn-danger btn-sm" disabled>
                                Cancelar
                            </a>
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
    <script>
        var $link, $imageUrl, $videoUrl;
        $(function () {
            $link = $('#link_container');
            $imageUrl = $('#image_url_container');
            $videoUrl = $('#video_url_container');

            $('#type').on('change', onChangePostType);
        });

        function onChangePostType() {
            var type = $(this).val();

            switch (type) {
                case "link":
                    $imageUrl.hide();
                    $videoUrl.hide();
                    $link.slideDown();
                    break;

                case "image":
                    $videoUrl.hide();
                    $link.hide();
                    $imageUrl.slideDown();
                    break;

                case "video":
                    $imageUrl.hide();
                    $link.hide();
                    $videoUrl.slideDown();
                    break;

                default:
                    $link.hide();
                    $imageUrl.hide();
                    $videoUrl.hide();
                    break;
            }
        }
    </script>
@endsection
