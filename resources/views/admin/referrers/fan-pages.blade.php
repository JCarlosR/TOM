@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Listado de fan pages del usuario {{ $referral->id }}</div>

            <div class="panel-body">

                <p>Listado de fan pages asociadas al usuario {{ $referral->name }}.</p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Fan page</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Fecha de registro</th>
                                <th>Promociones creadas</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($fan_pages as $fan_page)
                                <tr>
                                    <td>
                                        <a href="//facebook.com/{{ $fan_page->fan_page_id }}" target="_blank">
                                            {{ $fan_page->fan_page_id }}
                                        </a>
                                    </td>
                                    <td>{{ $fan_page->name }}</td>
                                    <td>{{ $fan_page->category }}</td>
                                    <td>{{ $fan_page->created_at }}</td>
                                    <td>{{ $fan_page->promotions_count }}</td>
                                    <td>
                                        <a href="{{ url('fan-page/'.$fan_page->id.'/promotions') }}" class="btn btn-info btn-sm" title="Ver promociones">
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