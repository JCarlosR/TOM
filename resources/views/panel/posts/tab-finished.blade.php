<h3>Publicaciones finalizadas</h3>
<p>Mostrando publicaciones que ya han finalizado, empezando por las más recientes.</p>
<p>En total ya has realizado {{ $finished_posts->count() }} publicaciones.</p>
@if ($finished_posts->count() > 0)
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Grupo destino</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($finished_posts as $post)
        <tr>
            <td>
                <a href="//fb.com/{{ $post->fb_destination_id }}" class="btn btn-info btn-sm" target="_blank">
                    <i class="fa fa-facebook-square"></i>
                </a>
            </td>
            <td>{{ $post->scheduled_date }}</td>
            <td>{{ substr($post->scheduled_time, 0, 5) }}</td>
            <td>{{ $post->type }}</td>
            <td>{{ $post->description }}</td>
            <td>
                <span class="label label-default">{{ $post->status }}</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $finished_posts->links() }}
@endif
