@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Listado de usuarios creadores</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <div class="text-right">
                    <a href="{{ url('/admin/creators/excel') }}" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-export"></span>
                        Exportar a Excel
                    </a>
                </div>

                <p>Listado de usuarios que tienen al menos una fanpage asociada a su registro en TOM.</p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
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
                                        {{--<a href="{{ url('admin/user/edit') }}" class="btn btn-primary btn-sm" title="Editar promoción">--}}
                                            {{--<span class="fa fa-pencil"></span>--}}
                                        {{--</a>--}}

                                        <a href="{{ url('admin/creator/'.$creator->id.'/fan-pages') }}" class="btn btn-info btn-sm" title="Ver fan pages">
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
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('¿Está seguro que desea dar de baja a esta usuario?');
        }
    </script>
@endsection