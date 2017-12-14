@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/emojionearea/emojionearea.css') }}">
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

                @if ($availablePermissions || env('APP_DEBUG'))
                    <form action="" id="formImage" style="display: none;">
                        <input type="file" id="inputImage">
                    </form>
                    <form action="{{ url('/facebook/posts') }}" method="POST" id="scheduleForm" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{--<div class="form-group">--}}
                            {{--<label for="type">Tipo de publicación</label>--}}
                            {{--<select name="type" id="type" required class="form-control">--}}
                                {{--<option value="">Seleccione un tipo de publicación</option>--}}
                                {{--<option value="link">Publicar un enlace</option>--}}
                                {{--<option value="image">Publicar una imagen</option>--}}
                                {{--<option value="images">Publicar varias imágenes</option>--}}
                                {{--<option value="video">Publicar un video</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>

                            <button id="btnImage" class="btn btn-default btn-xs" type="button" title="Subir imagen">
                                <i class="glyphicon glyphicon-picture"></i>
                            </button>
                        </div>
                        <div class="panel panel-default" id="previewLink" style="display: none">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="" class="img-responsive" data-cover>
                                    </div>
                                    <div class="col-md-8">
                                        <p data-title>Título</p>
                                        <p class="small" data-description>Descripción</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="uploadedImages">
                            <template id="templateImage">
                                <div class="col-md-2">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <img src="" class="img-responsive">
                                            <input type=hidden name=imageUrls[] value="">
                                            <button class="btn btn-xs btn-danger btn-block" data-remove="image" type="button">
                                                <i class="fa fa-remove"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="form-group">
                            <label for="scheduled_date">¿En qué fecha se publicará?</label>
                            <input type="date" class="form-control" name="scheduled_date" required value="{{ old('scheduled_date', date('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label for="scheduled_time">¿A qué hora se publicará?</label>
                            <input type="time" class="form-control" name="scheduled_time" required value="{{ old('scheduled_time', date('H:i')) }}">
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary btn-sm" id="scheduleButton">
                        <i class="fa fa-calendar"></i> Programar publicación
                    </button>

                @else
                    <p>Al parecer, la publicación en facebook no está disponible actualmente. Por favor informa de esto al administrador.</p>
                @endif

                <hr>

                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3MKTQ6ZZEQYU2" target="_blank" class="btn btn-info btn-sm">
                    Pago mensual paypal
                </a>
                <a href="https://compropago.com/comprobante/?id=babe6e2c-0df0-4bd7-a5a7-9461d744f4c3" target="_blank" class="btn btn-info btn-sm">
                    Pago anual efectivo
                </a>

                <a href="{{ url('/facebook/posts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-backward"></i>
                    Volver al listado de publicaciones programadas
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/vendor/emojionearea/emojionearea.js') }}"></script>
    <script>
        var $btnLoadImage, $formImage, $inputImage;
        var $scheduleBtn, $scheduleForm;
        var allowImageUpload = true;
        var $uploadedImages, $templateImage;
        var $description, $previewLink;

        function setupButtonImage() {
            $btnLoadImage = $('#btnImage');
            $formImage = $('#formImage');
            $inputImage = $('#inputImage');
            $uploadedImages = $('#uploadedImages');
            $templateImage = $('#templateImage');

            $btnLoadImage.on('click', function () {
                if (allowImageUpload) {
                    allowImageUpload = false;
                    $btnLoadImage.prop('disabled', true);
                    $inputImage.click();
                    allowImageUpload = true;
                    $btnLoadImage.prop('disabled', false);
                }
            });
            $inputImage.on('change', uploadImage);

            $(document).on('click', '[data-remove="image"]', removeImage);
        }

        function uploadImage() {
            allowImageUpload = false;
            $btnLoadImage.prop('disabled', true);

            var fileData = $inputImage.prop("files")[0];
            var formData = new FormData();
            formData.append('file', fileData);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '/facebook/posts/images',
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    console.log(data);

                    var uploadedImage = $templateImage.html();
                    uploadedImage = uploadedImage.replace('src=""', 'src="'+data.secure_url+'"');
                    uploadedImage = uploadedImage.replace('value=""', 'value="'+data.id+'"');
                    $uploadedImages.append(uploadedImage);

                    $previewLink.hide();

                    allowImageUpload = true;
                    $btnLoadImage.prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    allowImageUpload = true;
                    $btnLoadImage.prop('disabled', false);
                }
            });
        }

        function removeImage() {
            $(this).closest('.panel').remove();

            if ($('[name="imageUrls[]"]').size() === 0)
                checkLinkPreview($description.val()); // no change event required
        }

        $(function () {
            $scheduleBtn = $('#scheduleButton');
            $scheduleForm = $('#scheduleForm');
            $description = $("#description");
            $previewLink = $('#previewLink');

            $scheduleBtn.on('click', function () {
                $scheduleForm.submit();
            });

            setupButtonImage();
            setupEmojis();
            setupLinkDetector();
        });

        function setupEmojis() {
            $description.emojioneArea({
                pickerPosition: "bottom",
                tonesStyle: "bullet"
            });
        }

        function setupLinkDetector() {
            var delayTimer;
            // check for previews with delay
            function checkLinkWithDelay() {
                var text = $(this).val();
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    checkLinkPreview(text);
                }, 1000); // wait 1000 ms (1 s)
            }
            $description.on('change', checkLinkWithDelay);
        }

        function checkLinkPreview(text) {
            if ($('[name="imageUrls[]"]').size() > 0) // if there are images, avoid fetch
                return;

            if (text.indexOf('http') === -1) // minimum client side validation
                return;

            $.get('/link-preview', {'text': text}, function (data) {
                if (data) {
                    console.log(data);
                    renderPreview(data);
                }
            });
        }

        function renderPreview(data) {
            $previewLink.find('[data-cover]').attr('src', data.cover);
            $previewLink.find('[data-title]').text(data.title);
            $previewLink.find('[data-description]').text(data.description);
            $previewLink.fadeIn('slow');
        }
    </script>
@endsection
