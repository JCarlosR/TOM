@extends('layouts.dashboard')

@section('styles')
    <style>
        .img-center {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection

@section('dashboard_content')
    <div class="col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading">Administrar fan page</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('warning') }}</p>
                    </div>
                @endif

                <p>Información de la página seleccionada.</p>
                    <div class="well bs-component">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <img src="{{ $fanPage->picture_200 }}" alt="Picture - Fan page" class="img-rounded img-center">
                                <h2>{{ $fanPage->name }}</h2>
                                <p>
                                    <b>ID: </b>
                                    {{ $fanPage->fan_page_id }}
                                </p>
                                <p>
                                    <b>Categoría: </b>
                                    {{ $fanPage->category }}
                                </p>
                            </div>

                            <div class="col-md-6" id="panelOptions" @if (count($errors) > 0) style="display: none" @endif>
                                <h3>Opciones</h3>
                                {{--<a href="{{ url('config/page/'.$fanPage->id.'/picture') }}" class="btn btn-sm btn-primary btn-block">--}}
                                    {{--Actualizar foto--}}
                                {{--</a>--}}
                                <a href="#" class="btn btn-primary btn-block">
                                    Gestionar promociones
                                </a>
                                <button href="" class="btn btn-info btn-block" id="btnNewPromotion">
                                    Registrar nueva promoción
                                </button>
                            </div>
                            <div class="col-md-6" @if (count($errors) == 0) style="display: none" @endif id="panelNewPromotion">
                                <h3>Registrar nueva promoción</h3>
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ url('/promotion') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="fan_page_id" value="{{ $fanPage->id }}">
                                    <div class="form-group">
                                        <label for="description">Promoción</label>
                                        <textarea name="description" rows="2" placeholder="Describe aquí tu promoción. El límite es de 180 caracteres." class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">¿Hasta cuándo estará vigente?</label>

                                        <div class="radio">
                                            <label><input type="radio" name="infinite" value="1" checked>Promoción permanente (no aplica vigencia)</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="infinite" value="0">Seleccionar fecha de vigencia</label>
                                        </div>

                                        <input style="display: none" type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Sube la imagen de tu promoción</label>
                                        <input type="file" class="form-control" name="image" id="image">
                                    </div>
                                    <div class="form-group">
                                        <label for="attempts">Define la frecuencia de participantes ganadores <em>(Mín 1 y Máx 10)</em></label>
                                        <input type="number" class="form-control" placeholder="¿Cada cuántas veces se gana?" min="1" max="10" value="{{ old('attempts') }}" name="attempts">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            Registrar promoción
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var $endDate;

        $(function () {
            $endDate = $('#end_date');

            $('#btnNewPromotion').on('click', onClickNewPromotion);
            $('input[type=radio][name=infinite]').change(onChangeInfinite);
        });

        function onClickNewPromotion() {
            $('#panelOptions').slideUp();
            $('#panelNewPromotion').show();
        }

        function onChangeInfinite() {
            if (this.value == 1) {
                $endDate.slideUp();
            }
            else if (this.value == 0) {
                $endDate.slideDown();
            }
        }
    </script>
@endsection
