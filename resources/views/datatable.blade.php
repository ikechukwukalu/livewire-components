@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" id="table-container">
                <div class="card-header" id="card-header">{{ __('Livewire Datatable') }}
                    @if(isset($_GET['page']))&nbsp;-&nbsp;<b>Page:</b>&nbsp;{{ $_GET['page'] }} @endif</div>
                <div class="card-body">
                    @livewire('datatable', [
                        'order_by' => $order_by,
                        'page_options' => $page_options,
                        'pages_displayed' => isset($_GET['pages_displayed']) ? $_GET['pages_displayed'] : $pages_displayed,
                        'sort' => $sort,
                        'maxP' => $maxP
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection