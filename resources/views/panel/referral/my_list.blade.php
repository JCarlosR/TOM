@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Mis referidos</div>

            <div class="panel-body">
                <div class="text-right">
                    <a href="{{ url('/referrals/excel') }}" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-export"></span>
                        Exportar a Excel
                    </a>
                </div>

                <p>
                    Desde esta página podrás ver un listado de los usuarios que se han
                    registrado gracias a tu invitación.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fanpages</th>
                            <th>Fecha de registro</th>
                            <th>Participaciones restantes</th>
                            <th>Última participación</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($creators as $creator)
                            <tr>
                                <td>
                                    <a href="//facebook.com/{{ $creator->facebook_user_id }}" target="_blank" title="{{ $creator->facebook_user_id }}">
                                        {{ $creator->name }}
                                    </a>
                                </td>
                                <td>{{ $creator->email }}</td>
                                <td>{{ $creator->fanPagesCount }}</td>
                                <td>{{ $creator->created_at }}</td>
                                <td>{{ $creator->remaining_participations }}</td>
                                <td>{{ $creator->updated_at }}</td>
                                <td>
                                    <a href="{{ url('referral/'.$creator->id.'/fan-pages') }}" class="btn btn-info btn-sm" title="Ver fan pages">
                                        <span class="fa fa-eye"></span>
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
@endsection

@section('scripts')

@endsection
