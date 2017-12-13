<h3>Publicaciones programadas</h3>
<p>Actualmente tienes programadas {{ $scheduled_posts->count() }} publicaciones.</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Grupo destino</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scheduled_posts as $post)
        <tr>
            <td>
                <a href="//fb.com/{{ $post->fb_destination_id }}" class="btn btn-info btn-sm" target="_blank">
                    <i class="fa fa-facebook-square"></i>
                </a>
            </td>
            <td>{{ $post->scheduled_date }}</td>
            <td>{{ $post->scheduled_time }}</td>
            <td>{{ $post->type }}</td>
            <td>
                <span class="label label-default">{{ $post->status }}</span>
            </td>
            <td>
                <a href="" class="btn btn-primary btn-sm" disabled>
                    Ver contenido
                </a>
                <a href="{{ url('facebook/posts/delete?post_id='.$post->id) }}" class="btn btn-danger btn-sm"
                   onclick="return confirm('Está seguro que desea eliminar esta publicación?')">
                    Cancelar
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
