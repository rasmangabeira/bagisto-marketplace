@extends('shop::customers.account.index')

@section('page_title')
    {{ __('transactions') }}
@endsection

@section('page-detail-wrapper')
<div class="dashboard">
    <div class="account-head mb-10">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">
            {{ __('transactions') }}
        </span>
    </div>
        <div class="account-items-list">
            <div class="dashboard-stats" style="margin-top: 40px;"><div class="dashboard-card"><div class="title">
                        Total Sale
                    </div> <div class="data">
                        {{$statistics['total_sale']}}
                    </div></div> <div class="dashboard-card"><div class="title">
                        Total Payout
                    </div> <div class="data">
                       {{$statistics['total_payout']}}
                    </div></div> <div class="dashboard-card"><div class="title">
                        Remaining Payout
                    </div> <div class="data">
                        {{$statistics['remaining_payout']}}
                    </div></div>
                        
            </div>
            
            <div class="account-table-content">

                {!! app('Webkul\Marketplace\DataGrids\TransactionDataGrid')->render() !!}

            </div>
        </div></div>
@endsection