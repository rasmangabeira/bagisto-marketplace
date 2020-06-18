@extends('admin::layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.product.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('marketplace::app.admin.product.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('product_contents', 'Webkul\Marketplace\DataGrids\Admin\OrderDataGrid')
            {!! $product_contents->render() !!}
        </div>
    </div>
@stop