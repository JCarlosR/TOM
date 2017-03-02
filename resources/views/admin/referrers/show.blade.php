@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Referidos del usuario {{ $referrer->name }}</div>

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

                <p>Listado de usuarios que ha referido {{ $referrer->name }}.</p>

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
                                {{--<th>Opciones</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($referrals as $referral)
                                <tr>
                                    <td>
                                        <a href="//facebook.com/{{ $referral->facebook_user_id }}" target="_blank" title="{{ $referral->facebook_user_id }}">
                                            {{ $referral->name }}
                                        </a>
                                    </td>
                                    <td>{{ $referral->email }}</td>
                                    <td>{{ $referral->fanPagesCount }}</td>
                                    <td>{{ $referral->created_at }}</td>
                                    <td>{{ $referral->referrals_count }}</td>
                                    <td>{{ $referral->remaining_participations }}</td>
                                    {{--<td class="text-center">--}}
                                        {{--<a href="{{ url('admin/creator/'.$creator->id.'/fan-pages') }}" class="btn btn-info btn-sm btn-block" title="Ver fan pages">--}}
                                            {{--<span class="fa fa-list"></span>--}}
                                        {{--</a>--}}
                                    {{--</td>--}}
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