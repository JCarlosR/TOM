@if ($promotion->hasSmallImage())
    <div class="alert alert-warning">
        <strong>Importante!</strong>
        <a href="{{ url('/config/promotion/'.$promotion->id.'/edit') }}" style="color: white; text-decoration: underline;">
            Actualiza la imagen
        </a> de tu promoción.
        Recuerda que el tamaño mínimo sugerido por facebook es 1200x630.
    </div>
@endif