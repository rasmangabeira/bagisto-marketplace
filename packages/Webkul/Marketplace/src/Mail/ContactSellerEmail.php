<?php

namespace Webkul\Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactSellerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $data;

    /**
     * Create a new mailable instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\View\View
     */
    public function build()
    {
         return $this
                    ->from('abood.elfar@yahoo','abood')
                    ->to('abdullah@wewebit.com')
                    ->subject('new email')
                    ->html('hiiiiiiiii');
    }
}