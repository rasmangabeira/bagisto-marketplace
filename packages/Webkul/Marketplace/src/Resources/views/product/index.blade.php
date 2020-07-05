@extends('shop::customers.account.index')

@section('page_title')
    {{ __('marketplace::app.product.index.page-title') }}
@endsection

@section('page-detail-wrapper')
        <a href="{{ route('seller.products.create') }}" class="theme-btn light unset pull-right">
            {{ __('marketplace::app.product.index.add-product') }}
        </a>
    <div class="account-head mb-10">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">
             {{ __('marketplace::app.product.index.page-title') }}
        </span>
    </div>
        <div class="account-items-list">
            <div class="account-table-content">

                {!! app('Webkul\Marketplace\DataGrids\ProductDataGrid')->render() !!}

            </div>
        </div>
@endsection