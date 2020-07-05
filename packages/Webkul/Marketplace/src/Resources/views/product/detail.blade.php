@if($is_seller)
    <div class="seller-info">
        Sold By :
        <a href="{{route('seller.profile.index',$seller->url)}}">{{$seller->shop_title}} <i class="icon star-blue-icon"></i>
        </a>
    </div>
@endif