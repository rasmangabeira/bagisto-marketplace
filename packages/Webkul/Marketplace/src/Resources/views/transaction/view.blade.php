@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')

<div class="account-content">
    <div class="account-layout"><div class="account-head"><span class="account-heading">
                Transaction {{$transaction->transaction_id}}
            </span> <div class="account-action"></div> <span></span></div> <div class="sale-container"><div class="sale-section"><div class="account-table-content profile-page-content"><div class="table"><table><tbody><tr><th>
                                        Created At
                                    </th> <td>
                                        {{$transaction->created_at}}
                                    </td></tr> <tr><th>
                                        Payment Method
                                    </th> <td>
                                        {{$transaction->payment_method}}
                                    </td></tr> <tr><th>
                                        Total
                                    </th> <td>
                                        {{$transaction->grand_total}}
                                    </td></tr> <tr><th>
                                        Comment
                                    </th> <td>
                                        {{$transaction->comment}}
                                    </td></tr></tbody></table></div></div></div> <div class="sale-section"><div class="secton-title"><span>Order #7</span></div> <div class="section-content"><div class="table">
        <table>
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Total</th>
                      <th>Commission</th>
                      <th>Seller Total</th>
                  </tr>
              </thead> 
              <tbody>
                  @foreach($orderItems as $orderItem)
                  <tr>
                        <td data-value="Name">
                            {{$orderItem->name}}
                        </td>
                        <td data-value="Price">{{$orderItem->price}}</td>
                        <td data-value="Qty">{{$orderItem->qty_ordered}}</td>
                        <td data-value="Total">{{$orderItem->total}}</td>
                        <td data-value="Commission">{{$orderItem->commission}}</td>
                        <td data-value="Seller Total">{{$orderItem->seller_total}}</td>
                  </tr>
                  @endforeach
              </tbody>
        </table>
                                            </div>
                                            
                                            <div class="totals" style="float: right;">
                                                <table class="table sale-summary">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sub Total</td>
                                                            <td>-</td>
                                                            <td>{{$order->sub_total}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Shipping &amp; Handling</td>
                                                            <td>-</td>
                                                            <td>TODO</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tax</td>
                                                            <td>-</td>
                                                            <td>{{$order->tax_amount}}</td>
                                                        </tr> 
                                                        <tr class="bold">
                                                            <td>Commission</td>
                                                            <td>-</td>
                                                            <td>-{{$order->commission}}</td>
                                                        </tr>
                                                        <tr class="bold">
                                                            <td>Seller Total</td>
                                                            <td>-</td>
                                                            <td>{{$order->seller_total}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div></div></div></div>
</div>
@endsection