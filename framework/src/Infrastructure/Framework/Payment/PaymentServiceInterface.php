<?php 

namespace R2Packages\Framework\Infrastructure\Framework\Payment;


interface PaymentServiceInterface {

    function initiate(string $email, float $amount, string $reference);

    function verify(string $reference);

    function authorize(string $authorization_code, string $email, float $amount);

    /**
     * Get the authorization URL
     * @return string
     */
    public function getAuthUrl();

    /**
     * Get the reference
     * @return string
     */
    public function getReference();

    /**
     * Get the status
     * @return string
     */
    public function getStatus();

    /**
     * Get the error
     * @return string
     */
    public function getError();

    /**
     * Get the authorization code
     * @return string
     */
    public function getAuthorizationCode();

}