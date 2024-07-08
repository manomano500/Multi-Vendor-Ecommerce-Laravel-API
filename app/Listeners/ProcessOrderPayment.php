<?php

namespace App\Listeners;

use App\Services\PlutuService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessOrderPayment
{
    protected $plutuService;

    public function __construct(PlutuService $plutuService)
    {
        $this->plutuService = $plutuService;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

    }
}
