@if ($availablePermissions)
    <p>Enhorabuena! TOM dispone de un token para publicar a tu nombre.</p>
    <p class="small">Última actualización: {{ auth()->user()->fb_access_token_updated_at ?: 'Nunca' }}</p>
    <p class="small">Te recomendamos actualizar este token una vez por mes. Su duración promedio es de 2 meses.</p>
    <a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-key"></i> Solicitar nuevo token
    </a>
@else
    <p>Por favor otorga permisos a TOM para que pueda publicar a tu nombre.</p>
    <a href="{{ url('/facebook/publish-group-permissions') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-key"></i> Otorgar permisos a TOM
    </a>
@endif

<hr>