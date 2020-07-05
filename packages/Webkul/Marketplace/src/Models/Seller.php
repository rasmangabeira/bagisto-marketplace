<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Illuminate\Support\Facades\Storage;

class Seller extends Model implements SellerContract
{
    protected $table = 'sellers';
    
    protected $fillable = array('shop_title', 'url', 'tax_vat','phone','address1','address2','country','state','postcode','about_shop','twitter','facebook','youtube','instagram','skype','linked_in','pinterest','return_policy','shipping_policy','privacy_policy','meta_description','meta_keywords','customer_id','logo','banner','id','commission_percentage');
    
    /**
     * Get image url for the category image.
     */
    public function logo_url()
    {
        if (! $this->logo)
            return;

        return Storage::url($this->logo);
    }

    /**
     * Get image url for the category image.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }
    
    public function banner_url()
    {
        if (! $this->banner)
            return;

        return Storage::url($this->banner);
    }

    /**
     * Get image url for the category image.
     */
    public function getBannerUrlAttribute()
    {
        return $this->banner_url();
    }
}