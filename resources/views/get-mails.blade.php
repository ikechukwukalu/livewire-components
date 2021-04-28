@extends('layouts.app')

@section('content')
<div class="row justify-content-center m-3">
    <div class="col-md-12">
        <div wire:offline class="alert alert-danger">
            <strong>You are now offline!</strong> Please check your internet connection.
        </div>
    </div>
    <div class="col-md-6">
        @livewire('get-mails', [
            'email' => $sender,
            'webmails' => [],
            'root' => $folder
        ])
    </div>
    <div class="col-md-6">
        <div class="card m-3 shadow-sm">
            <div class="card-header">
                <h3>Email subject:&nbsp;<span class="text-info" id="email-subject"></span></h3>
            </div>
            <div class="card-body">
                <span class="content-body" style="display: none !important"></span>
                <iframe id="iframe1" style="width: 100%; height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection