@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading">Administrar fan page</div>

            <div class="panel-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                <p>Editar promoción seleccionada.</p>
                <div class="well bs-component">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            @include('includes.fan_page_data')
                        </div>

                        <div class="col-md-6">
                            <h2>Datos de la promoción</h2>
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="description">Promoción</label>
                                    <textarea name="description" rows="2" placeholder="Describe aquí tu promoción. El límite es de 180 caracteres." class="form-control">{{ old('description', $promotion->description) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="end_date">¿Hasta cuándo estará vigente?</label>

                                    <div class="radio">
                                        <label><input type="radio" name="infinite" value="1" @if(! old('end_date', $promotion->end_date)) checked @endif>Promoción permanente (no aplica vigencia)</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="infinite" value="0" @if(old('end_date', $promotion->end_date)) checked @endif>Seleccionar fecha de vigencia</label>
                                    </div>

                                    <input @if(! old('end_date', $promotion->end_date)) style="display: none" @endif type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date', $promotion->end_date) }}">
                                </div>
                                <div class="form-group">
                                    <label for="image">Actualiza la imagen <em>(subir solo si se desea cambiar)</em></label>
                                    <input type="file" class="form-control" name="image" id="image">
                                </div>
                                <div class="form-group">
                                    <label for="attempts">Define la frecuencia de participantes ganadores <em>(Mín 1 y Máx 10)</em></label>
                                    <input type="number" class="form-control" placeholder="¿Cada cuántas veces se gana?" min="1" max="10" value="{{ old('attempts', $promotion->attempts) }}" name="attempts">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
