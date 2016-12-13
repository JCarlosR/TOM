@extends('layouts.app')

@section('styles')
    <style>
        .img-responsive {
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">{{ $promotion->description }}</div>

                <div class="panel-body text-center">
                    <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">
                </div>

                {{-- This user has liked the page? --}}
                <button class="btn btn-primary">
                    Participar en el sorteo
                </button>

                <p>Ya has participado 3 veces en este sorteo !</p>
            </div>
        </div>
    </div>
</div>
@endsection
