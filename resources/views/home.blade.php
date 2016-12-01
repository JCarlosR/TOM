@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>You are logged in!</p>
                    @if (session('message'))
                        <p>{{ session('message') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
