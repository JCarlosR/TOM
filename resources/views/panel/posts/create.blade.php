@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.css">
@endsection

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
                    <form action="{{ url('/facebook/posts') }}" method="POST" id="scheduleForm">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="type">Tipo de publicación</label>
                            <select name="type" id="type" required class="form-control">
                                <option value="">Seleccione un tipo de publicación</option>
                                <option value="link">Publicar un enlace</option>
                                <option value="image">Publicar una imagen</option>
                                <option value="images">Publicar varias imágenes</option>
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
                    </form>

                    <form action="{{ url('/facebook/posts/images') }}" method="post" style="margin-bottom: 1em; display: none;"
                          class="dropzone" id="my-dropzone">
                        {{ csrf_field() }}
                    </form>

                    <button type="button" class="btn btn-primary btn-sm" id="scheduleButton">
                        <i class="fa fa-calendar"></i> Programar publicación
                    </button>

                    @else
                    <p>Antes de programar una publicación, por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>
                @endif

                <hr>

                <a href="{{ url('/facebook/posts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-backward"></i>
                    Volver al listado de publicaciones programadas
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
    <script>
        var $link, $imageUrl, $imagesZone, $videoUrl;
        var $scheduleBtn, $scheduleForm;

        setupDropzone();

        function setupDropzone() {
            Dropzone.autoDiscover = false;
            dropzoneOptions = {
                acceptedFiles: 'image/*',
                url: '{{ url('/facebook/posts/images') }}',
                dictDefaultMessage: 'Arrastre fotos o imágenes a esta sección'
            };
            var uploader = document.querySelector('#my-dropzone');
            var myDropzone = new Dropzone(uploader, dropzoneOptions);

            myDropzone.on("success", function(file, response) {
                if (response.id) {
                    var hiddenInputImageId = '<input type=hidden name=imageUrls[] value="'+response.id+'">';
                    $scheduleForm.append(hiddenInputImageId);
                }
            });
        }

        $(function () {
            $link = $('#link_container');
            $imageUrl = $('#image_url_container');
            $imagesZone = $('#my-dropzone');
            $videoUrl = $('#video_url_container');

            $scheduleBtn = $('#scheduleButton');
            $scheduleForm = $('#scheduleForm');

            $scheduleBtn.on('click', function () {
                $scheduleForm.submit();
            });
            $('#type').on('change', onChangePostType);
        });

        function onChangePostType() {
            var type = $(this).val();

            switch (type) {
                case "link":
                    $imageUrl.hide();
                    $videoUrl.hide();
                    $imagesZone.hide();
                    $link.slideDown();
                    break;

                case "image":
                    $videoUrl.hide();
                    $link.hide();
                    $imagesZone.hide();
                    $imageUrl.slideDown();
                    break;

                case "images":
                    $videoUrl.hide();
                    $link.hide();
                    $imageUrl.hide();
                    $imagesZone.slideDown();
                    break;

                case "video":
                    $imageUrl.hide();
                    $link.hide();
                    $imagesZone.hide();
                    $videoUrl.slideDown();
                    break;

                default:
                    $imagesZone.hide();
                    $link.hide();
                    $imageUrl.hide();
                    $videoUrl.hide();
                    break;
            }
        }
    </script>
@endsection
