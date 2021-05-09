@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-100 shadow-sm mb-3">
            <a href="{{ route('datatable') }}">
                <img class="card-img-top" src="{{ asset('images/components/livewire-datatable.png')}}"
                    alt="livewire datatable">
                    </a>
                <div class="card-body">
                    <h4 class="card-title" align="center"><a href="{{ route('datatable') }}">Livewire Datatable</a></h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card w-100 shadow-sm mb-3">
            <a href="{{ route('get-mails') }}">
                <img class="card-img-top" src="{{ asset('images/components/livewire-getmail.png')}}"
                    alt="livewire imap emails">
                    </a>
                <div class="card-body">
                    <h4 class="card-title" align="center"><a href="{{ route('get-mails') }}">Livewire Imap Emails</a></h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card w-100 shadow-sm mb-3">
            <a href="{{ route('infinite-scroll') }}">
                <img class="card-img-top" src="{{ asset('images/components/livewire-infinitescroll.png')}}"
                    alt="livewire infinite scroll">
                    </a>
                <div class="card-body">
                    <h4 class="card-title" align="center"><a href="{{ route('infinite-scroll') }}">Livewire Infinite Scroll</a></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

</script>
@endsection