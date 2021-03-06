@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productRepository', 'Webkul\Marketplace\Repositories\SellerProductRepository')

@extends('shop::layouts.master')

@section('page_title')
 {{ __('products')  }} -{{$seller->url}}
@stop

@section('seo')

@stop

@push('css')
    <style type="text/css">
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }

        @media only screen and (max-width: 992px) {
            .main-content-wrapper .vc-header {
                box-shadow: unset;
            }
        }
    </style>
@endpush

@php
   /* $isDisplayMode = in_array(
        $category->display_mode, [
            null,
            'products_only',
            'products_and_description'
        ]
    );
*/

$isDisplayMode = 1;
    $products = $productRepository->getAll($seller->id);
@endphp

@section('content-wrapper')
<div class="main">
    <div class="profile-container">
        <div class="profile-top-block mb15">
            <div class="profile-information padding-15">
                <img height="120" width="120" src="{{ $seller->logo_url }}">
                <div class="profile-information-block"><a href="{{route('seller.profile.index',$seller->url)}}" class="shop-title">{{$seller->url}}</a> <label class="shop-address">
                 
                </label></div>
            </div>
        </div>
    </div>
    <category-component></category-component>
</div>    
@stop

@push('scripts')
    <script type="text/x-template" id="category-template">
        <section class="row col-12 velocity-divide-page category-page-wrapper">
           
    
            @if (1)
                @include ('shop::products.list.layered-navigation')
            @endif
    
            <div class="category-container right">
                <div class="row remove-padding-margin">
                    <div class="pl0 col-12">
                        <h1 class="fw6 mb10"></h1>
    
                        @if ($isDisplayMode)
                            <template v-if="products.length > 0">
                              
                            </template>
                        @endif
                    </div>
    
                    <div class="col-12 no-padding">
                        <div class="hero-image">
                            
                        </div>
                    </div>
                </div>
    
                <div class="filters-container">
                    @include ('shop::products.list.toolbar')
                </div>
    
                <div
                    class="category-block">

                    @if ($isDisplayMode)
                        <shimmer-component v-if="isLoading && !isMobile()" shimmer-count="4"></shimmer-component>

                        <template v-else-if="products.length > 0">
                            @if ($toolbarHelper->getCurrentMode() == 'grid')
                                <div class="row col-12 remove-padding-margin">
                                    <product-card
                                        :key="index"
                                        :product="product"
                                        v-for="(product, index) in products">
                                    </product-card>
                                </div>
                            @else
                                <div class="product-list">
                                    <product-card
                                        list=true
                                        :key="index"
                                        :product="product"
                                        v-for="(product, index) in products">
                                    </product-card>
                                </div>
                            @endif
    
                            
    
                            <div class="bottom-toolbar">
                                {{ $products->appends(request()->input())->links() }}
                            </div>
    
                            
                        </template>
    
                        <div class="product-list empty" v-else>
                            <h2>{{ __('shop::app.products.whoops') }}</h2>
                            <p>{{ __('shop::app.products.empty') }}</p>
                        </div>
                    @endif
                </div>
            </div>
    
          
        </section>
    </script>

    <script>
        Vue.component('category-component', {
            template: '#category-template',

            data: function () {
                return {
                    'products': [],
                    'isLoading': true,
                    'paginationHTML': '',
                }
            },

            created: function () {
                this.getCategoryProducts();
            },

            methods: {
                'getCategoryProducts': function () {
                    this.$http.get(`${this.$root.baseUrl}/seller-products/{{ $seller->id }}${window.location.search}`)
                    .then(response => {
                        this.isLoading = false;
                        this.products = response.data.products.data;
                        this.paginationHTML = response.data.paginationHTML;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.log(this.__('error.something_went_wrong'));
                    })
                }
            }
        })
    </script>
@endpush