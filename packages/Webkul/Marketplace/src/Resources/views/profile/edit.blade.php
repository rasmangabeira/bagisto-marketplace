@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head seller-profile-edit mb-10">
        <span class="account-heading">
            {{ __('Edit Seller Profile') }}
        </span>
        <div class="account-action">
            <a href="{{route('seller.shop.productOrCategory.index',$seller->url)}}" target="_blank" class="btn btn-black btn-lg theme-btn">
                View Collection page
            </a>
            <a href="{{route('seller.profile.index',$seller->url)}}" target="_blank" class="btn btn-black btn-lg theme-btn">
                View Seller Page
            </a>
        </div>
    </div>

   
        <div class="profile-update-form">
            <form
                method="POST"
                @submit.prevent="onSubmit"
                class="account-table-content"
                enctype="multipart/form-data"
                action="{{ route('seller.profile.update') }}">
                @csrf
              
            
                <accordian :title="'{{ __('marketplace::app.seller.account.profile.general') }}'" :active="true">
                   
                    <div slot="body">
                <div :class="`row ${errors.has('shop_title') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.shopTitle') }}
                    </label>

                    <div class="col-12">
                        <input value="{{ $seller->shop_title }}" name="shop_title" type="text" v-validate="'required'" />
                        <span class="control-error" v-if="errors.has('shop_title')">@{{ errors.first('shop_title') }}</span>
                    </div>
                </div>
                <div :class="`row ${errors.has('url') ? 'has-error' : ''}`">
                    <label class="col-12">
                        {{ __('marketplace::app.seller.account.profile.shopUrl') }}
                    </label>

                    <div class="col-12">
                        <input value="{{ $seller->url }}" name="url" type="text" />
                        <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                    </div>
                </div>
                
                <div :class="`row ${errors.has('tax_vat') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.tax') }}
                    </label>
                    <div class="col-12">
                        <input value="{{ $seller->tax_vat }}" name="tax_vat" type="text" />
                        <span class="control-error" v-if="errors.has('tax_vat')">@{{ errors.first('tax_vat') }}</span>
                    </div>
                </div>
                <div :class="`row ${errors.has('phone') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.phone') }}
                    </label>
                    <div class="col-12">
                        <input value="{{ $seller->phone }}" name="phone" type="text" />
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>
                </div>
                <div :class="`row ${errors.has('address1') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.address1') }}
                    </label>
                    <div class="col-12">
                        <input value="{{ $seller->address1 }}" name="address1" type="text" />
                        <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                    </div>
                </div>
                <div :class="`row ${errors.has('address2') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.address2') }}
                    </label>
                    <div class="col-12">
                        <input value="{{ $seller->address2 }}" name="address2" type="text" />
                        <span class="control-error" v-if="errors.has('address2')">@{{ errors.first('address2') }}</span>
                    </div>
                </div>
                <div :class="`row ${errors.has('postcode') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('marketplace::app.seller.account.profile.postcode') }}
                    </label>
                    <div class="col-12">
                        <input value="{{ $seller->postcode }}" name="postcode" type="text" />
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>
                </div>
                @include ('shop::customers.account.address.country-state', ['countryCode' => old('country'), 'stateCode' => $seller->state,'defaultCountry'=>$seller->country])
                </div>
                 </accordian> 
                
                <accordian :title="'{{ __('marketplace::app.seller.account.profile.media') }}'" >
                    <div slot="body">
                  
                        <div>
                            <div class="form-group">
                                <label>{{ __('marketplace::app.seller.account.profile.logo') }}</label>
                                  <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo_img" :multiple="false"  :images='"{{ $seller->logo_url }}"'></image-wrapper>
                            </div>
                            <div class="form-group">
                                <label>{{ __('marketplace::app.seller.account.profile.banner') }}</label>
                                  <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="banner_img" :multiple="false"  :images='"{{ $seller->banner_url }}"'></image-wrapper>
                            </div>
                        </div>
                  
                        </div>
                </accordian> 
                
                
                 <accordian :title="'{{ __('marketplace::app.seller.account.profile.Social Links') }}'" >
                    
                     <div slot="body">
                        <div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.twitter') }}
                                </label>
                                <input value="{{ $seller->twitter }}" name="twitter" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.facebook') }}
                                </label>
                                <input value="{{ $seller->facebook }}" name="facebook" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.youtube') }}
                                </label>
                                <input value="{{ $seller->youtube }}" name="youtube" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.instagram') }}
                                </label>
                                <input value="{{ $seller->instagram }}" name="instagram" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.skype') }}
                                </label>
                                <input value="{{ $seller->skype }}" name="skype" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.linked_in') }}
                                </label>
                                <input value="{{ $seller->linked_in }}" name="linked_in" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.pinterest') }}
                                </label>
                                <input value="{{ $seller->pinterest }}" name="pinterest" type="text" />
                            </div>
                        </div>
                    </div>
                    
                </accordian> 
                <accordian :title="'{{ __('marketplace::app.seller.account.profile.About Shop') }}'" >
                    
                    <div slot="body">
                        <div>
                            <input class="tinymce" value="{{ $seller->about_shop }}" name="about_shop" type="text" />
                        </div>
                    </div>
                 </accordian> 
                
                 <accordian :title="'{{ __('marketplace::app.seller.account.profile.policies') }}'" >
                     
           
                   
                     <div slot="body">
                        <div>
                            <div class="form-group">
                                <label for="return_policy">{{ __('marketplace::app.seller.account.profile.return_policy') }}</label>
                            <input class="tinymce" value="{{ $seller->return_policy }}" name="return_policy" type="text" />
                            </div>
                            
                            <div class="form-group">
                                <label for="shipping_policy">{{ __('marketplace::app.seller.account.profile.shipping_policy') }}</label>
                                <input class="tinymce" value="{{ $seller->shipping_policy }}" name="shipping_policy" type="text" />
                            </div>
                            <div class="form-group">
                                <label for="privacy_policy">{{ __('marketplace::app.seller.account.profile.privacy_policy') }}</label>
                                <input class="tinymce" value="{{ $seller->privacy_policy }}" name="privacy_policy" type="text" />
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    
                 </accordian> 
                
                <accordian :title="'{{ __('marketplace::app.seller.account.profile.SEO') }}'" >
                    
                     <div slot="body">
                        <div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.meta_description') }}
                                </label>
                                <input value="{{ $seller->meta_description }}" name="meta_description" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="col-12">
                                    {{ __('marketplace::app.seller.account.profile.meta_keywords') }}
                                </label>
                                <input value="{{ $seller->meta_keywords }}" name="meta_keywords" type="text" />
                            </div>
                        </div>
                    </div>
                 </accordian> 
               

                <button
                    type="submit"
                    class="theme-btn mb20">
                    {{ __('velocity::app.shop.general.update') }}
                </button>
            </form>
        </div>





@endsection

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            var acc = document.getElementsByClassName("accordian6");
            var i;

            for (i = 0; i < acc.length; i++) {
              acc[i].addEventListener("click", function() {

                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                  panel.style.maxHeight = null;
                } else {
                  panel.style.maxHeight = panel.scrollHeight + "px";
                }
              });
            }
            tinymce.init({
                height: 200,
                width: "100%",
                image_advtab: true,
                valid_elements : '*[*]',
                selector: 'input.tinymce',
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            });
        });
    </script>
@endpush