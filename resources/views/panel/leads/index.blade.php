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

                <a href="{{ url('/clientes/excel') }}" class="btn btn-info btn-sm pull-right">
                    Descargar como Excel
                </a>

                <p><strong>Felicidades por tus posibles clientes!</strong></p>
                <p>Ahora dales seguimiento hasta conseguir la venta.</p>

                <p class="text-muted">Listado de usuarios que han participado en tus promociones.</p>

                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#status1" aria-controls="status1" role="tab" data-toggle="tab">A contactar</a></li>
                        <li role="presentation"><a href="#status2" aria-controls="status2" role="tab" data-toggle="tab">En progreso</a></li>
                        <li role="presentation"><a href="#status3" aria-controls="status3" role="tab" data-toggle="tab">Con venta</a></li>
                        <li role="presentation"><a href="#status44" aria-controls="status44" role="tab" data-toggle="tab">Sin venta</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="status1">
                            @include('includes.panel.leads.participations-table', ['participations' => $participations_1])
                        </div>
                        <div role="tabpanel" class="tab-pane" id="status2">
                            @include('includes.panel.leads.participations-table', ['participations' => $participations_2])
                        </div>
                        <div role="tabpanel" class="tab-pane" id="status3">
                            @include('includes.panel.leads.participations-table', ['participations' => $participations_3])
                        </div>
                        <div role="tabpanel" class="tab-pane" id="status44">
                            @include('includes.panel.leads.participations-table', ['participations' => $participations_4])
                        </div>
                    </div>

                </div>

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
            $('table').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
//                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { targets: [2, 3, 5, 6, 9], className: 'none' }
                ],
                "drawCallback": function(settings, json) {
                    $("[data-score]").rateYo({
                        rating: $(this).data('value'), starWidth: '18px', fullStar: true, numStars: 3, maxValue: 3
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
            $(document).on('[data-status]', 'change', onChangeLeadStatus);
            function onChangeLeadStatus() {
                var id = $(this).data('id');
                var newStatus = $(this).val();
                var postData = {
                    status: newStatus,
                    _token: csrfToken
                };
                $.post('{{ url('/api/participation') }}/'+id+'/status', postData, function (data) {
                    location.reload();
                }, 'json');
            }
        });
    </script>
@endsection
