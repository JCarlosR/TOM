@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
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
                                <div data-score="{{ $participation->id }}" data-rateyo-rating="{{ $participation->stars }}"></div>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-notes="edit" data-id="{{ $participation->id }}">
                                    <span class="fa fa-edit"></span>
                                </button>
                            </td>
                            <td>
                                <select class="form-control" data-status data-id="{{ $participation->id }}">
                                    <option value="A contactar" @if ($participation->status=='A contactar') selected @endif>A contactar</option>
                                    <option value="En progreso" @if ($participation->status=='En progreso') selected @endif>En progreso</option>
                                    <option value="Con venta" @if ($participation->status=='Con venta') selected @endif>Con venta</option>
                                    <option value="Sin venta" @if ($participation->status=='Sin venta') selected @endif>Sin venta</option>
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
                    <button type="button" id="notes-submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        $(document).ready(function(){
            // CSRF
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // initialize data tables
            $('#clients-table').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { targets: [2, 3, 5, 6], className: 'none' }
                ],
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                "drawCallback": function(settings, json) {
                    $("[data-score]").rateYo({
                        rating: $(this).data('value'), starWidth: '20px', fullStar: true
                    }).on("rateyo.set", function (e, data) {
                        var rating = data.rating;
                        var postData = { stars: rating, _token: csrfToken };
                        $.post('{{ url('/api/participation') }}/'+$(this).data('score')+'/stars', postData, function () {
                        }, 'json');
                    });
                }
            });

            // notes edit in modal
            var selectedParticipationId;
            var $modalEditNotes = $('#modal-edit-notes');
            $('[data-notes]').on('click', onEditDataNotes);

            function onEditDataNotes() {
                selectedParticipationId = $(this).data('id');
                $.get('{{ url('/api/participation') }}/'+selectedParticipationId+'/notes', function (data) {
                    $('#notes').val(data.notes);
                    $modalEditNotes.modal('show');
                });
            }

            // perform update for notes
            $('#notes-submit').on('click', function () {
                var postData = {
                    notes: $('#notes').val(),
                    _token: csrfToken
                };
                $.post('{{ url('/api/participation') }}/'+selectedParticipationId+'/notes', postData, function (data) {
                    if (data.success)
                        $modalEditNotes.modal('hide');
                }, 'json');
            });

            // participation lead status
            $('[data-status]').on('change', onChangeLeadStatus);
            function onChangeLeadStatus() {
                var id = $(this).data('id');
                var newStatus = $(this).val();
                var postData = {
                    status: newStatus,
                    _token: csrfToken
                };
                $.post('{{ url('/api/participation') }}/'+id+'/status', postData, function (data) {
                }, 'json');
            }
        });
    </script>
@endsection
