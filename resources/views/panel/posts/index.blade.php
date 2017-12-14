@extends('layouts.dashboard')

@section('styles')
    <style>
        .tab-content {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            border-radius: 0 0 4px 4px;
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

                {{--@if ($availablePermissions)--}}
                    {{--<p>Enhorabuena! TOM dispone de un token para publicar a tu nombre.</p>--}}
                    {{--<p class="small">Última fecha de actualización del token: {{ auth()->user()->fb_access_token_updated_at ?: 'Nunca' }}</p>--}}
                    {{--<p class="small">Te recomendamos actualizar este token una vez por mes. Su duración promedio es de 2 meses.</p>--}}
                    {{--<a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-sm btn-primary">--}}
                        {{--<i class="fa fa-key"></i> Solicitar nuevo token--}}
                    {{--</a>--}}
                {{--@else--}}
                    {{--<p>Antes de programar una publicación, por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>--}}
                    {{--<a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-primary btn-sm">--}}
                        {{--<i class="fa fa-key"></i> Otorgar permisos a TOM--}}
                    {{--</a>--}}
                {{--@endif--}}
                {{--<hr>--}}

                <p>¿Deseas programar una nueva publicación?</p>
                <a href="{{ url('/facebook/posts/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                    Nueva publicación
                </a>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Publicaciones programadas</div>

            <div class="panel-body">
                <ul class="nav nav-tabs nav-justified">
                    <li>
                        <a data-toggle="tab" href="#tab-finished">
                            Posts finalzados
                        </a>
                    </li>
                    <li class="active">
                        <a data-toggle="tab" href="#tab-future">
                            Posts futuros
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-finished" class="tab-pane fade">
                        @include('panel.posts.tab-finished')
                    </div>
                    <div id="tab-future" class="tab-pane fade in active">
                        @include('panel.posts.tab-future')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
