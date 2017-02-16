@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Promociones de la fan page {{ $fan_page->id }}</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Listado de promociones asociadas a la fan page {{ $fan_page->name }}.</p>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Vigencia</th>
                                <th>Ganar cada</th>
                                <th>Participaciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($promotions as $promotion)
                                <tr>
                                    <td>
                                        <a href="{{ url('/promotion/'.$promotion->id) }}" target="_blank">
                                            {{ $promotion->description }}
                                        </a>
                                    </td>
                                    <td>{{ $promotion->end_date }}</td>
                                    <td>{{ $promotion->attempts }}</td>
                                    <td>{{ $promotion->participations_count }}</td>
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