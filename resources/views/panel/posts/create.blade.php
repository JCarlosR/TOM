@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicar en grupos de <i class="fa fa-facebook-square"></i></div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Antes de programar una publicación, por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>
                <a href="#" class="btn btn-primary btn-sm">Otorgar permisos a TOM</a>

                <hr>

                <form action="">
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
                        <textarea name="description" id="description" class="form-control"></textarea>
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
                        <input type="date" class="form-control" name="scheduled_date">
                    </div>
                    <div class="form-group">
                        <label for="scheduled_time">¿A qué hora se publicará?</label>
                        <input type="time" class="form-control" name="scheduled_time">
                    </div>
                    <button class="btn btn-primary btn-sm">
                        <i class="fa fa-calendar"></i> Programar publicación
                    </button>
                </form>
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
                    <tr>
                        <td>
                            <a href="//fb.com/948507005305322" class="btn btn-info btn-sm">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </td>
                        <td></td>
                        <td></td>
                        <td>Enlace</td>
                        <td>
                            <span class="label label-default">Pendiente</span>
                        </td>
                        <td>
                            <a href="" class="btn btn-primary btn-sm">
                                Ver contenido
                            </a>
                            <a href="" class="btn btn-danger btn-sm">
                                Cancelar
                            </a>
                        </td>
                    </tr>
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
