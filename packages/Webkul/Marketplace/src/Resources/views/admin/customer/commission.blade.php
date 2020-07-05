@if( $customer->is_seller )
    <accordian :title="'{{ __('marketplace::app.admin.customer.commission') }}'" :active="true">
        <div slot="body">
            <div>
                <div class="control-group">
                    <div class="control-group">
                        <label for="commission_percentage">
                            {{ __('marketplace::app.admin.customer.commission_percentage') }}
                        </label>
                        <input type="text" name="commission_percentage" class="control" value="{{old('commission_percentage') ?:$seller->commission_percentage}}"> 
                    </div>
                </div>
            </div>
        </div>
    </accordian>  
@endif