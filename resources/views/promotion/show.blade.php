@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">{{ $promotion->description }}</div>

                <div class="panel-body">
                    <img src="{{ asset('/images/promotions/'.$promotion->image_path) }}" alt="TOM Promo" class="img-responsive">
                </div>

                {{-- This user has liked the page? --}}
            </div>
        </div>
    </div>
</div>
@endsection
