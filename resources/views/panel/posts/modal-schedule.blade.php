<div class="modal fade" id="modalSchedule" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agendar publicación</h4>
            </div>
            <div class="modal-body text-center" id="modalBody">
                <div class="form-group">
                    <label for="scheduled_date">¿En qué fecha se publicará?</label>
                    <input type="hidden" id="scheduledDate" class="form-control" name="scheduled_date" required value="{{ old('scheduled_date', date('Y-m-d')) }}">
                    <div id="date-container"></div>
                </div>
                <div class="form-group">
                    <label for="scheduled_time">¿A qué hora se publicará?</label>
                    <input type="time" id="scheduledTime" class="form-control" name="scheduled_time" required value="{{ old('scheduled_time', $time) }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="scheduleButton">Agendar publicación</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>