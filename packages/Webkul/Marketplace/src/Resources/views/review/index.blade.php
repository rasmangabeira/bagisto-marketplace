@extends('shop::customers.account.index')

@section('page_title')
    {{ __('reviews') }}
@endsection

@section('page-detail-wrapper')
        
    <div class="account-head mb-10">
        

        <span class="account-heading">
             {{ __('reviews') }}
        </span>
    </div>
        <div class="account-items-list">
            <div class="account-table-content">

                {!! app('Webkul\Marketplace\DataGrids\ReviewDataGrid')->render() !!}

            </div>
        </div>
@endsection