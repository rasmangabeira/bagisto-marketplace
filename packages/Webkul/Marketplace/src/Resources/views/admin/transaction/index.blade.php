@extends('admin::layouts.content')

@section('page_title')
    {{ __('transactions') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('transactions') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('product_contents', 'Webkul\Marketplace\DataGrids\Admin\TransactionDataGrid')
            {!! $product_contents->render() !!}
        </div>
    </div>
@stop