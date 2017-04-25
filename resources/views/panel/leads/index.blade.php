@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('/vendor/star-rating/css/star-rating.min.css') }}">
@endsection

@section('dashboard_content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">Listado de potenciales clientes</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p><strong>Felicidades por tus posibles clientes!</strong></p>
                <p>Ahora dales seguimiento hasta conseguir la venta.</p>

                <p class="text-muted">Listado de usuarios que han participado en tus promociones.</p>

                <table class="table table-bordered table-hover" id="clients-table">
                    <thead>
                    <tr>
                        <th>Nombre</th> <!-- 0 -->
                        <th>E-mail</th> <!-- 1 -->
                        <th>Ubicación</th> <!-- 2 -->
                        <th>Fanpage</th> <!-- 3 -->
                        <th>Promoción</th> <!-- 4 -->
                        <th>Resultado</th> <!-- 5 -->
                        <th>Fecha</th> <!-- 6 -->
                        <th>Calificación</th> <!-- 7 -->
                        <th>Notas</th> <!-- 8 -->
                        <th>Status</th> <!-- 9 -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($participations as $participation)
                        <tr>
                            <td>
                                <a href="//fb.com/{{ $participation->user->facebook_user_id }}" target="_blank">
                                    {{ $participation->user->name }}
                                </a>
                            </td>
                            <td>{{ $participation->user->email }}</td>
                            <td>{{ $participation->user->location_name }}</td>
                            <td>
                                <a href="//fb.com/{{ $participation->promotion->fanPage->fan_page_id }}" target="_blank" title="Fanpage que capturó el lead">
                                    Visitar
                                </a>
                            </td>
                            <td>
                                <a href="{{ $participation->promotion->fullLink }}" target="_blank" title="Promoción en la que participó">
                                    {{ $participation->promotion->description }}
                                </a>
                            </td>
                            <td>{{ $participation->is_winner ? 'Ganó' : 'Perdió' }}</td>
                            <td>{{ $participation->created_at }}</td>
                            <td>
                                <input data-rating="{{ $participation->id }}" type="number" class="rating" data-min="0" data-max="4" data-stars="4" data-step="1" data-size="xs">
                            </td>
                            <td>
                                <button class="btn btn-primary" data-notes="edit">
                                    <span class="fa fa-edit"></span>
                                </button>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="A contactar">A contactar</option>
                                    <option value="En progreso">En progreso</option>
                                    <option value="Con venta">Con venta</option>
                                    <option value="Sin venta">Sin venta</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modal-edit-notes">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Notas</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notes">Contenido</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Volver sin guardar</button>
                    <button type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function(){
            // initialize data tables
            $('#clients-table').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { targets: [2, 3, 5, 6, 7], className: 'none' }
                ],
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });

            // notes edit in modal
            var $modalEditNotes = $('#modal-edit-notes');
            $('[data-notes]').on('click', onEditDataNotes);

            function onEditDataNotes() {
                $modalEditNotes.modal('show');
            }

            // stars

        });
    </script>

    <script src="{{ asset('/vendor/star-rating/js/star-rating.min.js') }}"></script>
    <script>
        $('[data-rating]').on('rating.change', function(event, value, caption) {
            console.log(value);
            console.log(id);
        });
    </script>
@endsection
