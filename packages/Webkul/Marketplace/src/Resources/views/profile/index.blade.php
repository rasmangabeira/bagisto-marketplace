@extends('shop::layouts.master')
@section('seo')
    <meta name="description" content="{{ trim($seller->meta_description) != "" ? $seller->meta_description : str_limit(strip_tags($seller->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $seller->meta_keywords }}"/>
@stop
@section('page_title')
    {{ $seller->url }}
@endsection

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
                    </div>
                    <div class="row">
                        <div class="review-info">
                            <span class="number">
                                {{$averageRatingForSeller}}
                            </span>
                            <div class="total-reviews">
                                <a href="">
                                    {{$numRatingForSeller}} Ratings &amp; {{$numCommentForSeller}} Reviews
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row social-links">
                        
                    </div>
                    <div class="row">
                        <a href="javascript:void(0)" @click="showModal('contactForm')">Contact Seller</a>
                    </div>
                    <div class="row">
                        <a href="{{route('seller.shop.productOrCategory.index',$seller->url)}}">
                             {{$productCount}} products
                        </a>
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
<modal id="contactForm" :is-open="modalIds.contactForm">
    <h3 style="margin-left: 80px;" slot="header">Contact Seller</h3>

    <i class="icon remove-icon "></i>

    <div slot="body">
        <contact-seller-form></contact-seller-form>
    </div>
</modal>

@endsection
@push('scripts')
<script type="text/x-template" id="contact-form-template">

        <form action="" class="account-table-content" method="POST" data-vv-scope="contact-form" @submit.prevent="contactSeller('contact-form')">

            <input type="hidden" name="_token" value="VZbCLuFpLdDj9IvOaXAt5wNqX4ihHB1YtSmjpyAG">
            <div class="form-container">

                <div class="form-group" >
                    <label for="name" class="required mandatory">Name</label>
                    <input type="text" v-model="contact.name" class="form-style control" name="name" v-validate="'required'" data-vv-as="&quot;Name&quot;">
             
                </div>

                <div class="form-group" >
                    <label for="email" class="required mandatory">Email</label>
                    <input type="text" v-model="contact.email" class="form-style control" name="email" v-validate="'required|email'" data-vv-as="&quot;Email&quot;">
                   
                </div>

                <div class="form-group" >
                    <label for="subject" class="required mandatory">Subject</label>
                    <input type="text" v-model="contact.subject" class="control form-style" name="subject" v-validate="'required'" data-vv-as="&quot;Subject&quot;">
                  
                </div>

                <div class="form-group" >
                    <label for="query" class="required mandatory">Query</label>
                    <textarea class="control form-style" v-model="contact.query" name="query" v-validate="'required'"  data-vv-as="&quot;Query&quot;">
                    </textarea>
                  
                </div>

                <button type="submit" class="btn btn-lg btn-primary theme-btn" :disabled="disable_button">
                    Submit
                </button>

            </div>

        </form>

    </script>
<script>
        Vue.component('contact-seller-form', {

            data: () => ({
                contact: {
                    'name': '',
                    'email': '',
                    'subject': '',
                    'query': ''
                },

                disable_button: false,
            }),

            template: '#contact-form-template',

            created () {

                
            },

            methods: {
                contactSeller (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{route('seller_contact')}}", this.contact)
                                .then (function(response) {
                                    this_this.disable_button = false;

                                    this_this.$parent.closeModal();

                                    window.flashMessages = [{
                                        'type': 'alert-success',
                                        'message': response.data.message
                                    }];

                                    this_this.$root.addFlashMessages()
                                })

                                .catch (function (error) {
                                    this_this.disable_button = false;

                                    this_this.handleErrorResponse(error.response, 'contact-form')
                                })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                },

                handleErrorResponse (response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }
        });

    </script>
    @endpush
