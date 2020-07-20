@if($is_seller && $seller->bo_status)
    <div class="seller-info">
        Sold By :
        <a href="{{route('seller.profile.index',$seller->url)}}">{{$seller->shop_title}} <i class="icon star-blue-icon"></i>
        </a>
    </div>
@endif