@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" id="table-container">
                <div class="card-header" id="card-header">{{ __('Livewire Datatable') }}
                    @if(isset($_GET['page']))&nbsp;-&nbsp;<b>Page:</b>&nbsp;{{ $_GET['page'] }} @endif</div>
                <div class="card-body">
                    @livewire('datatable', [
                        'columns' => $columns,
                        'order_by' => isset($_GET['column']) && isset($_GET['order']) ? [$_GET['column'], $_GET['order'] == 'asc' ? true : false] : $order_by,
                        'page_options' => $page_options,
                        'fetch' => isset($_GET['fetch']) ? $_GET['fetch'] : $fetch,
                        'sort' => $sort,
                        'maxP' => $maxP
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection