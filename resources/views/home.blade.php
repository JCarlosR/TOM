@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('message'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p>{{ session('message') }}</p>
                        </div>
                    @endif

                    <h3>Bienvenido {{ auth()->user()->name }} !</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
