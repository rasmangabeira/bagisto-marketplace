@extends('admin::layouts.content')

@section('page_title')
    {{ __('order') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('order') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('product_contents', 'Webkul\Marketplace\DataGrids\Admin\OrderDataGrid')
            {!! $product_contents->render() !!}
        </div>
    </div>

 <script type="text/x-template" id="order-grid-template">
   <modal id="payForm" :is-open="$root.$root.modalIds.payForm">
                <h3 slot="header">Pay Seller</h3>

                <div slot="body">
                    <form action="{{route('admin.order.createInvoice')}}" method="POST" data-vv-scope="pay-form" @submit.prevent="onSubmit($event)">
                         @csrf() 
                        <div class="form-container">

                            <input type="hidden" name="order_id" :value="order_id"/>
                            <input type="hidden" name="seller_id" :value="seller_id"/>
                            <input type="hidden" name="remaining" :value="remaining"/>
                            <input type="hidden" name="seller_total" :value="seller_total"/>
                            <input type="hidden" name="seller_name" :value="seller_name"/>
                                



                            <div class="control-group" 
                                <label for="comment" class="required">Comment</label>
                                <textarea class="control" name="comment" v-validate="'required'" data-vv-as="&quot;Comment&quot;">
                                </textarea>
                                <span class="control-error" v-if="errors.has('pay-form.comment')"></span>
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary">
                                Pay
                            </button>

                        </div>

                    </form>

                </div>
            </modal>
            </script>
  <order-grid></order-grid>
@stop
@push('scripts')
<script>
    Vue.component('order-grid', {
            template: "#order-grid-template",

            data: () => ({
                order_id: null,
                seller_id: null,
                remaining: null,
                seller_total: null,
                seller_name: null
            }),

            created() {
                var this_this = this;

                $(document).ready(function() {
                    $('.pay-btn').on('click', function(e) {
                        this_this.order_id = $(e.target).attr('data-id');
                        this_this.seller_id = $(e.target).attr('seller-id');
                        this_this.remaining = $(e.target).attr('data-remaining');
                        this_this.seller_total = $(e.target).attr('data-seller-total');
                        this_this.seller_name = $(e.target).attr('data-seller-name');
                        this_this.$root.$root.showModal('payForm')
                    });
                });
            },

            methods: {
                onSubmit (e) {
                    this.$validator.validateAll('pay-form').then((result) => {
                        if (result) {
                            e.target.submit();
                        }
                    });
                }
            }
        });
</script>
@endpush