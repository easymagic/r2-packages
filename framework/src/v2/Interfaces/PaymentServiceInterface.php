<?php 
namespace App\v2\Interfaces;

interface PaymentServiceInterface
{
    /**
     * Initiate a payment
     * @param string $email
     * @param float $amount
     * @param string $reference
     * @return array
     */
    public function initiate($email, $amount, $reference);

    /**
     * Verify a payment
     * @param string $reference
     * @return array
     */
    public function verify($reference);
    public function getAuthUrl();
    public function getReference();
    public function getStatus();
    public function getError();
    public function getAuthorizationCode();

    /**
     * Authorize a payment
     * @param string $authorization_code
     * @param string $email
     * @param float $amount
     * @return array
     */
    public function authorize($authorization_code, $email, $amount);

}