@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')
<div class="account-content">
    <div class="account-layout">
<div class="account-head">
    
</div>
<div class="sale-container">
  
        
      
        <tabs>
            <tab name="{{ __('shop::app.customer.account.order.view.info') }}" :selected="true">
                <div class="account-table-content profile-page-content">
                    <div class="table">
                        <table>
                            <tbody>
                                <tr><td>
                                        Placed On
                                    </td> <td>
                                       {{$order->created_at}}
                                    </td></tr> <tr><td>
                                        Status
                                    </td> <td>
                                         {{$order->state}}
                                    </td></tr> <tr><td>
                                        Customer Name
                                    </td> <td>
                                         {{$order->customer_first_name}} {{$order->customer_last_name}}
                                    </td></tr> <tr><td>
                                        Email
                                    </td> <td>
                                         {{$order->customer_email}}
                                    </td></tr> <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="sale-section">
                    <div class="secton-title"><span>Products Ordered</span></div>
                    <div class="section-content">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Item Status</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Admin Commission</th>
                                        <th>Tax Amount</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($order->items as  $orderItem)
                                     <tr>
                                         <td>{{$orderItem->sku}}</td>
                                         <td>{{$orderItem->name}}</td>
                                         <td>{{$orderItem->price}}</td>
                                         <td>
                                             @if($orderItem->qty_ordered)
                                                ordered({{$orderItem->qty_ordered}})
                                             @endif
                                             @if($orderItem->qty_shipped)
                                                shipped({{$orderItem->qty_shipped}})
                                             @endif
                                             @if($orderItem->qty_canceled)
                                                canceled({{$orderItem->qty_canceled}})
                                             @endif
                                             @if($orderItem->qty_refunded)
                                                refunded({{$orderItem->qty_refunded}})
                                             @endif
                                         </td>
                                         <td>{{$orderItem->total}}</td>
                                         <td>{{$orderItem->discount_amount}}</td>
                                         <td>{{$orderItem->getCommissionAttribute($order->commission_percent)}}</td>
                                         <td>{{$orderItem->tax_amount}}</td>
                                         <td>{{$orderItem->grand_total}}</td>
                                     </tr>
                                     @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="totals">
                            <span class="dash-icon">-</span>
                            <span class="dash-icon">-</span>
                            <table class="sale-summary">
                                <tbody>
                                    <tr>
                                        <td>
                                            Subtotal
                                            <span class="dash-icon">-</span>
                                        </td>
                                        <td>{{$order->sub_total}}</td>
                                    </tr> 
                                    <tr>
                                        <td>Shipping &amp; Handling
                                            <span class="dash-icon">-</span>
                                        </td> <td>$10.00</td>
                                    </tr>
                                    <tr><td>Discount
                                            <span class="dash-icon">-</span></td> <td>{{$order->discount_amount}}</td></tr> 
                                    <tr class="border"><td>Tax
                                            <span class="dash-icon">-</span></td> <td>{{$order->tax_amount}}</td></tr> 
                                    <tr class="bold"><td>Grand Total
                                            <span class="dash-icon">-</span></td> <td>{{$order->grand_total}}</td></tr>
                                    <tr class="bold"><td>Total Paid
                                            <span class="dash-icon">-</span></td> <td>{{$order->total_paid}}</td></tr> 
                                    <tr class="bold"><td>Total Refunded
                                            <span class="dash-icon">-</span></td> <td>{{$order->sub_total_refunded}}</td></tr> 
                                    <tr class="bold"><td>Total Due
                                            <span class="dash-icon">-</span></td> <td>$0.00</td></tr> 
                                    <tr class="bold"><td> Total Seller Amount
                                        </td> <td>{{$order->seller_total}}</td></tr>
                                    
                                    <tr class="bold"><td>Total Admin Commission
                                        </td> <td>{{$order->commission}}</td></tr>
                                </tbody><tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </tab>
            
            @if ($order->invoices->count())
            <tab name="{{ __('Invoices') }}" >
                
                
               @foreach($invoice_ids as $invoice_id) 
                
                
                <div class="sale-section">
                    <div class="secton-title"><span>Invoice #{{$invoice_id}}</span>
                        <a href="{{route('seller.printinvoice',$invoice_id)}}?order={{$order->id}}" class="pull-right">
                            Print
                        </a>
                    </div>
                    <div class="section-content">
                        <div class="table">
                            <table>
                                <thead><tr><th>Name</th> <th>Price</th> <th>Qty</th> <th>Subtotal</th> <th>Tax Amount</th> <th>Discount</th><th>Grand Total</th> </tr></thead> <tbody>
                                    
                                    @foreach($invoiceItems[$invoice_id] as  $invoiceItem)
                                    
                                       <tr>
                                        <td data-value="Name">
                                            {{$invoiceItem->name}}
                                        </td>
                                        <td data-value="Price">
                                             {{$invoiceItem->price}}
                                        </td>
                                        <td data-value="Qty">
                                            {{$invoiceItem->qty}}
                                        </td>
                                        <td data-value="Subtotal">
                                            {{$invoiceItem->total}}
                                        </td>
                                        <td data-value="Tax Amount">
                                            {{$invoiceItem->tax_amount}}
                                        </td>
                                        <td data-value="Discount">
                                            {{$invoiceItem->discount_amount}}
                                        </td>
                                        <td data-value="Grand Total">
                                            {{$invoiceItem->total + $invoiceItem->tax_amount}}
                                        </td>
                                    </tr>
                                    @endforeach
                                 
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    
                </div>
               @endforeach
             </tab>
            @endif
            @if ($shipmentItems->count())
            <tab name="{{ __('Shipments') }}">
                @foreach($shipments as $shipment) 
                <div class="sale-section">
                    <div class="section-title"><span>Shipment #{{$shipment->id}}</span></div>
                    <div class="section-content">
                        <div class="table">
                            <table>
                                <tbody><tr><th>Inventory Source</th> <td>{{$shipment->inventory_source_name}}</td></tr> <tr><th>Carrier Title</th> <td>{{$shipment->carrier_title}}</td></tr> <tr><th>
                                            Tracking Number
                                        </th> <td>
                                            <a class="zulu-dial zulu-tel-to-dial" href="tel:{{$shipment->track_number}}" data-zulu-num="{{$shipment->track_number}}" data-dial-id="0">{{$shipment->track_number}}&nbsp;<span class="zulu-icon-phone"></span></a>
                                        </td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table" style="margin-top: 20px;">
                            <table>
                                <thead>
                                    <tr><th>SKU</th> <th>Name</th> <th>Qty</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($shipmentItems[$shipment->id] as $shipmentItem)
                                    <tr>
                                        <td>{{$shipmentItem->sku}}</td>
                                        <td>{{$shipmentItem->name}}</td>
                                        <td>{{$shipmentItem->qty}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </tab>
             @endif
             @if ($refundItems->count())
            <tab name="{{ __('Refunds') }}">
                @foreach($refunds as $refund)
                <div class="sale-section">
                    <div class="section-title"><span>Refund #{{$refund->id}}</span></div>
                    <div class="section-content">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Tax Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refundItems[$refund->id] as $refundItem)
                                    <tr>
                                        <td>{{$refundItem->sku}}</td>
                                        <td>{{$refundItem->name}}</td>
                                        <td>{{$refundItem->price}}</td>
                                        <td>{{$refundItem->qty}}</td>
                                        <td>{{$refundItem->total}}</td>
                                        <td>{{$refundItem->tax_amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="totals">
                            <table class="sale-summary">
                                <tbody>
                                    <tr><td>Subtotal
                                                        <span class="dash-icon">-</span></td> <td>{{$refund->sub_total}}</td></tr>
                                    <tr><td>Adjustment Refund
                                                        <span class="dash-icon">-</span></td> <td>{{$refund->adjustment_refund}}</td></tr>
                                    <tr><td>Adjustment Fee
                                                        <span class="dash-icon">-</span></td> <td>{{$refund->adjustment_fee}}</td></tr> 
                                    <tr class="fw6"><td>Grand Total
                                                        <span class="dash-icon">-</span></td> <td>{{$refund->grand_total}}</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </tab>
               @endif
              </tabs>
            
            <div class="sale-section"><div class="section-content" style="border-bottom: 0px none;"><div class="order-box-container"><div class="box"><div class="box-title">
                                Shipping Address
                            </div> <div class="box-content">
<?php  $address = $order->billing_address; ?>
              {{ $address->name }}</br>
{{ $address->address1 }}</br>
{{ $address->city }}</br>
 {{ $address->state }}</br>
{{ core()->country_name($address->country) }} {{ $address->postcode }}</br></br>
{{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }}

                            </div></div> <div class="box"><div class="box-title">
                                Billing Address
                            </div> <div class="box-content">

<?php  $address = $order->shipping_address; ?>
              {{ $address->name }}</br>
{{ $address->address1 }}</br>
{{ $address->city }}</br>
 {{ $address->state }}</br>
{{ core()->country_name($address->country) }} {{ $address->postcode }}</br></br>
{{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }}
                            </div></div> <div class="box"><div class="box-title">
                                Shipping Method
                            </div> <div class="box-content">

                               {{$order->shipping_method}}

                            </div></div> <div class="box"><div class="box-title">
                                Payment Method
                            </div> <div class="box-content">
                               {{$order->payment->method}}
                            </div></div></div></div></div>
            
       
    
</div>
</div></div>
@endsection