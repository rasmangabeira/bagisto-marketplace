@extends('shop::layouts.master')

@section('content-wrapper')
<div class="main">
    <div class="profile-container">
        <div class="profile-left-block">
            <div class="content">
                <div class="profile-logo-block">
                    <img src="{{ $seller->logo_url }}" />
                </div>
                <div class="profile-information-block">
                    <div class="row">
                        <h2 class="shop-title">{{ $seller->shop_title }}</h2>
                        <a target="_blank" href="https://www.google.com/maps/place/chilu, GJ, India" class="shop-address">chilu, GJ (India)</a>
                    </div>
                    <div class="row social-links">
                        
                    </div>
                    <div class="row">
                        <a href="#">Contact Seller</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-right-block">
            <img src="{{ $seller->banner_url }}" />
        </div>
    </div>
    <div class="profile-details padding-15">
        <div class="profile-details-left-block section">
            <div class="slider-container">
                
            </div>
            <accordian :title="'Return Policy'" :active="true">
                <div slot="body">{{ $seller->return_policy }}</div>
            </accordian>
            <accordian :title="'Shipping Policy'" :active="true">
                <div slot="body">{{ $seller->shipping_policy }}</div>
            </accordian>
            <accordian :title="'Privacy Policy'" :active="true">
                <div slot="body">{{ $seller->privacy_policy }}</div>
            </accordian>
        </div>
        <div class="profile-details-right-block section">
            <div class="section-heading"><h2>
                        About Seller<br> <span class="seperator"></span></h2>
            </div>
            <div class="section-content">
                {!! $seller->about_shop !!}
            </div>
        </div>
    </div>
    
</div>
@endsection