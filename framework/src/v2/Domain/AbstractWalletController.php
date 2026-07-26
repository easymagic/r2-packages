<?php 

namespace App\v2\Domain;

abstract class AbstractWalletController {

    protected $paymentService;

    public function __construct(PaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

}