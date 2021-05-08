@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Livewire Infinite Scroll') }}</div>
                <div class="card-body">
                    @livewire('infinite-scroll')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection