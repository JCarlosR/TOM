@extends('layouts.dashboard')

@section('styles')

@endsection

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Instrucciones</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Felicidades ya tienes configurada tu TomboFans y para que funcione realiza lo siguiente:</p>
                <div class="well bs-component">
                    <p>
                        1. Copia el link único de tu promo:
                        <input type="text" class="form-control" value="{{ url("/promotion/$promotionId") }}">
                    </p>
                    <p>
                        2. Crea un botón en tu fanpage y selecciona el que dice “Jugar”.
                        <em>Si ya tienes un botón activo, cambia el texto por “Jugar”.</em>
                        <br>Y en donde dice sitio web, coloca el link del paso anterior y guarda los cambios.
                    </p>
                    <p>
                        3.
                        <a href="https://fb.com/{{ $fanPageFbId }}" target="_blank">Da clic aquí para ir a tu fanpage</a> y hacer el paso 1 y 2.
                    </p>
                    <p>
                        4. Ahora solo comparte tu fanpage, comenta que den clic en jugar, y recibe por correo a tus nuevos clientes.
                    </p>
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
