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

                <p>Listado de usuarios que tienen al menos una fanpage asociada a su registro en TOM.</p>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fanpages</th>
                                <th>Fecha de registro</th>
                                <th>Participaciones restantes</th>
                                <th>Última participación</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($creators as $creator)
                                <tr>
                                    <td>{{ $creator->name }}</td>
                                    <td>{{ $creator->email }}</td>
                                    <td>{{ $creator->fanPagesCount }}</td>
                                    <td>{{ $creator->created_at }}</td>
                                    <td>{{ $creator->remaining_participations }}</td>
                                    <td>{{ $creator->updated_at }}</td>
                                    {{--<td>--}}
                                        {{--<a href="{{ url('admin/user/edit') }}" class="btn btn-primary btn-sm" title="Editar promoción">--}}
                                            {{--<span class="fa fa-pencil"></span>--}}
                                        {{--</a>--}}

                                        {{--<a href="{{ url('admin/user/'.$creator->id.'/fanpages') }}" class="btn btn-info btn-sm" title="Ver fanpages">--}}
                                            {{--<span class="fa fa-link"></span>--}}
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
            return confirm('¿Está seguro que desea eliminar esta promoción?');
        }
    </script>
@endsection