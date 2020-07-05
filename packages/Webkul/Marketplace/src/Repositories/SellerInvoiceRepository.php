<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerInvoiceRepository extends Repository
{
    public function model(): string {
        return 'Webkul\Marketplace\Contracts\SellerInvoice';
    }
}