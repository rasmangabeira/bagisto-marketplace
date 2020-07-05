@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.address.create.page-title') }}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/velocity.css') }}" />



@stop

@section('page-detail-wrapper')



    <div class="account-head mb-15">
        <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
        <span class="account-heading">{{ __('shop::app.customer.account.address.create.title') }}</span>
        
      
        <span></span>
    </div>

   
         <form method="POST" action="" @submit.prevent="onSubmit">
            <input type="hidden" name="is_seller" value="1" />

              <div class="account-action"><button type="submit" class="btn btn-lg theme-btn">
                        Create
                    </button></div>
            <div class="account-table-content">
                 <div class="page-content">
                @csrf
                <?php $familyId = request()->input('family') ?>

                <accordian :title="'{{ __('admin::app.catalog.products.general') }}'" :active="true">
                    <div slot="body">
                        
                        <div>
                            <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                                <label for="type" class="required mandatory">Product Type</label>
                                <select class="control" v-validate="'required'" id="type" name="type" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.product-type') }}&quot;">

                                @foreach($productTypes as $key => $productType)
                                    <option value="{{ $key }}" {{ request()->input('type') == $productType['key'] ? 'selected' : '' }}>
                                        {{ $productType['name'] }}
                                    </option>
                                @endforeach

                            </select>
                            @if ($familyId)
                                <input type="hidden" name="type" value="{{ app('request')->input('type') }}"/>
                            @endif
                         <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                              <label for="attribute_family_id" class="required mandatory">Attribute Family</label>  
                               <select class="control" v-validate="'required'" id="attribute_family_id" name="attribute_family_id" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                                <option value=""></option>
                                @foreach ($families as $family)
                                    <option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
                                    @endforeach
                            </select>

                            @if ($familyId)
                                <input type="hidden" name="attribute_family_id" value="{{ $familyId }}"/>
                            @endif
                               <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span> 
                            </div>
                            <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                                <label for="sku" class="required mandatory">SKU</label>
                                 <input type="text" v-validate="{ required: true, regex: /^[a-z0-9]+(?:-[a-z0-9]+)*$/ }" class="control" id="sku" name="sku" value="{{ request()->input('sku') ?: old('sku') }}" data-vv-as="&quot;{{ __('admin::app.catalog.products.sku') }}&quot;"/>
                                
                                 <span class="control-error" v-if="errors.has('sku')">@{{ errors.first('sku') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    
                </accordian>
                
                 @if ($familyId)

                 

                    <accordian :title="'{{ __('admin::app.catalog.products.configurable-attributes') }}'" :active="true">
                        <div slot="body">


                            <div class="table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin::app.catalog.products.attribute-header') }}</th>
                                            <th>{{ __('admin::app.catalog.products.attribute-option-header') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($configurableFamily->configurable_attributes as $attribute)
                                            <tr>
                                                <td>
                                                    {{ $attribute->admin_name }}
                                                </td>
                                                <td>
                                                    @foreach ($attribute->options as $option)
                                                        <span class="label">
                                                            <input type="hidden" name="super_attributes[{{$attribute->code}}][]" value="{{ $option->id }}"/>
                                                            {{ $option->admin_name }}

                                                            <i class="icon cross-icon"></i>
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="actions">
                                                    <i class="icon trash-icon"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </accordian>

                @endif
               
                 </div>
            </div>
        </form>

@endsection  