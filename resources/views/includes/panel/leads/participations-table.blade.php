<div class="panel panel-default">
    <div class="panel-body">

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Nombre</th> <!-- 0 -->
                <th>E-mail</th> <!-- 1 -->
                <th>Ubicación</th> <!-- 2 -->
                <th>Fanpage</th> <!-- 3 -->
                <th>Promoción</th> <!-- 4 -->
                <th>Resultado</th> <!-- 5 -->
                <th>Fecha</th> <!-- 6 -->
                <th>Rating</th> <!-- 7 -->
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
                            {{ $participation->promotion->fanPage->name }}
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
