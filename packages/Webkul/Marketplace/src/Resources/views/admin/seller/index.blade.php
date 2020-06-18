@extends('admin::layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.seller.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('marketplace::app.admin.seller.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('seller_contents', 'Webkul\Marketplace\DataGrids\Admin\SellerDataGrid')
            {!! $seller_contents->render() !!}
        </div>
    </div>
@stop