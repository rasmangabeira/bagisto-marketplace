@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')
        <a href="{{ route('seller.products.create') }}" class="theme-btn light unset pull-right">
            Add Product
        </a>
    <div class="account-head mb-10">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">
            {{ __('shop::app.customer.account.order.index.title') }}
        </span>
    </div>
        <div class="account-items-list">
            <div class="account-table-content">

                {!! app('Webkul\Marketplace\DataGrids\OrderDataGrid')->render() !!}

            </div>
        </div>
@endsection