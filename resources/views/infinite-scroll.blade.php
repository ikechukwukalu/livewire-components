@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Livewire Infinite Scroll') }}</div>
                <div class="card-body">
                    @livewire('infinite-scroll', [
                        'no_user' => 2,
                        'page' => 1,
                        'message' => "No more users",
                        'fetch' => $fetch,
                        'cache_time' => $cache_time,
                        'users' => $users
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection