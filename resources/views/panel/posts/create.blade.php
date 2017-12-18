@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/emojionearea/emojionearea.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datepicker/datepicker.min.css') }}">
    <style>
        .datepicker-container {
            margin: 0 auto;
        }
        [name=scheduled_time] {
            width: 200px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
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
                        <input type="file" id="inputImage" multiple>
                    </form>
                    <form action="{{ url('/facebook/posts') }}" method="POST" id="scheduleForm" enctype="multipart/form-data">
                        {{ csrf_field() }}

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

                        @include('panel.posts.modal-schedule')

                        {{-- Dropdown button --}}
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-thumbs-up"></i> Programar publicación
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" id="nowButton"><i class="fa fa-bolt"></i> Publicar ahora</a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#modalSchedule"><i class="fa fa-calendar"></i> Agendar publicación</a>
                                </li>
                            </ul>
                        </div>
                    </form>
                @else
                    <p>Al parecer, la publicación en facebook no está disponible actualmente. Por favor informa de esto al administrador.</p>
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
    <script src="{{ asset('/vendor/emojionearea/emojionearea.js') }}"></script>
    <script src="{{ asset('/vendor/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ asset('/vendor/datepicker/datepicker.es-ES.js') }}"></script>
    <script>
        var $btnLoadImage, $formImage, $inputImage;
        var $scheduleBtn, $scheduleForm, $nowBtn;
        var allowImageUpload = true;
        var $uploadedImages, $templateImage;
        var $description, $previewLink;
        var $scheduledDate, $scheduledTime;

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
            $inputImage.on('change', uploadImages);

            $(document).on('click', '[data-remove="image"]', removeImage);
        }

        function uploadImages() {
            allowImageUpload = false;
            $btnLoadImage.prop('disabled', true);

            var filesData = $inputImage.prop("files");
            for (var i=0; i<filesData.length; ++i) {
                postAjaxImageData(filesData[i]);
            }
        }

        function postAjaxImageData(fileData) {
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
            $nowBtn = $('#nowButton');
            $description = $("#description");
            $previewLink = $('#previewLink');
            $scheduledDate = $('#scheduledDate');
            $scheduledTime = $('#scheduledTime');

            $scheduleBtn.on('click', sendScheduledPost);
            $nowBtn.on('click', function () {
                // $('#modalSchedule').remove();
                $scheduleForm.append('<input type="hidden" name="now" id="now" value="1">');
                sendScheduledPost();
            });

            setupButtonImage();
            setupEmojisAndLinkDetector();

            $scheduledDate.datepicker({
                inline: true,
                container: '#date-container',
                startDate: 'today',
                format: 'yyyy-mm-dd',
                language: 'es-ES'
            });
        });

        var awaitingResponse = false;
        function sendScheduledPost() {
            if (awaitingResponse)
                return;

            var formData = $scheduleForm.serialize();
            awaitingResponse = true;
            $.ajax({
                type: "POST",
                url: $scheduleForm.attr('action'),
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.success)
                        performSuccessAnimation();
                },
                error: function(errMsg) {
                    displayErrors(errMsg.responseJSON);
                },
                complete: function () {
                    awaitingResponse = false;
                }
            });
        }

        function displayErrors(errorsArray) {
            var $target;
            if (document.getElementById('now')) {
                $('#now').remove();
                $target = $scheduleForm;
            } else {
                $target = $('#modalBody');
            }

            var alertHtml =
                '<div class="alert alert-danger alert-dismissable">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '{message}' +
                '</div>';
            // console.log(errorsArray);

            for (var prop in errorsArray) {
                if (errorsArray.hasOwnProperty(prop)) {
                    // console.log("prop: " + prop + " value: " + errorsArray[prop])
                    var errorMessages = errorsArray[prop];
                    // console.log(errorMessages);
                    for (var i=0; i<errorMessages.length; ++i) {
                        var $alertMessage = alertHtml.replace('{message}', errorMessages[i]);
                        $target.prepend($alertMessage);
                    }
                }
            }
        }

        function performSuccessAnimation() {
            $('#modalSchedule').modal('hide'); // possibly open
            $scheduleForm.slideUp('slow', function () {
                var successMessage;
                if (document.getElementById('now'))
                    successMessage = 'Listo! Tu publicación será publicada de acuerdo a tus instrucciones.';
                else
                    successMessage = 'Listo! Tu publicación se ha programado con éxito.';
                var successHtml =
                    '<div class="alert alert-success alert-dismissable">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        successMessage +
                    '</div>' +
                    '<p>En breve serás redirigido al listado de publicaciones ...</p>';
                $scheduleForm.before(successHtml);
                setTimeout(function () {
                    location.href = '/facebook/posts';
                }, 5200);
            });
        }

        function setupEmojisAndLinkDetector() {
            var delayTimer;
            // check for previews with delay
            function checkLinkWithDelay() {
                var text = el[0].emojioneArea.getText();
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    checkLinkPreview(text);
                }, 1000); // wait 1000 ms (1 s)
            }

            var el = $description.emojioneArea({
                pickerPosition: "bottom",
                tonesStyle: "bullet",
                events: {
                    keyup: checkLinkWithDelay
                }
            });
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
