@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Listado de usuarios reclutadores</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                {{--<div class="text-right">--}}
                    {{--<a href="{{ url('/admin/creators/excel') }}" class="btn btn-success btn-sm">--}}
                        {{--<span class="glyphicon glyphicon-export"></span>--}}
                        {{--Exportar a Excel--}}
                    {{--</a>--}}
                {{--</div>--}}

                <p>Listado de usuarios que tienen al menos un referido.</p>
                <p class="text-muted"><strong>En desarrollo:</strong> Esta sección se modificará para listar usuarios que tienen al menos una visita en su link de referidos.</p>

                    <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fanpages</th>
                                <th>Fecha de registro</th>
                                <th>Referidos</th>
                                <th>Participaciones restantes</th>
                                <th>Más info</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($referrers as $referrer)
                                <tr>
                                    <td>
                                        <a href="//facebook.com/{{ $referrer->facebook_user_id }}" target="_blank" title="{{ $referrer->facebook_user_id }}">
                                            {{ $referrer->name }}
                                        </a>
                                    </td>
                                    <td>{{ $referrer->email }}</td>
                                    <td>{{ $referrer->fanPagesCount }}</td>
                                    <td>{{ $referrer->created_at }}</td>
                                    <td>{{ $referrer->referrals_count }}</td>
                                    <td>{{ $referrer->remaining_participations }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('admin/referrer/'.$referrer->id) }}" class="btn btn-info btn-sm btn-block" title="Ver referidos">
                                            <span class="fa fa-list"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('¿Está seguro que desea dar de baja a esta usuario?');
        }
    </script>
@endsection