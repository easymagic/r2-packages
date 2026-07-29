<?php 

namespace R2Packages\Framework\Services;

use App\v2\Interfaces\PaymentServiceInterface;
use Exception;

class PaymentService implements PaymentServiceInterface {

    private Registry $registry;

    public $auth_url = '';
    public $reference = '';
    public $status = false;
    public $authorization_code = '';
    public $error = '';
    public $result = [];

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    /** @var string */
    const PAYSTACK_BASE_URL = 'https://api.paystack.co';
    /** @var string */
    // const PAYSTACK_SECRET_KEY = 'sk_test_73e90b261d2375678421f1c559423a0e74113f18';
    // /** @var string */
    // const PAYSTACK_PUBLIC_KEY = 'pk_test_a53d3dd04a3afdb96eebcdebd0bcfde7e4d54e7d';


    // private function getPaystackSecretKey()
    // {
    //     global $config;
    //     // Get Paystack secret key from config
    //     // You should add 'paystack_secret_key' to your config array
    //     return isset($config['paystack_secret_key']) ? $config['paystack_secret_key'] : '';
    // }

    /**
     * Initiate a payment
     * @param string $email
     * @param float $amount
     * @param string $reference
     * @return array
     */
    function initiate($email, $amount, $reference){
        
        $secretKey = $this->registry->get('paystack_secret_key');

        $url = self::PAYSTACK_BASE_URL . "/transaction/initialize";
        
        $fields = [
            'email' => $email,
            'amount' => $amount * 100, // Convert to kobo (Paystack expects amount in kobo)
            //'callback_url' => $payment->callback_url
            'reference' => $reference,
        ];

        $fields_string = http_build_query($fields);

        // Initialize curl
        $ch = curl_init();
        
        // Set curl options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $secretKey,
            "Cache-Control: no-cache",
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute curl request
        $response = curl_exec($ch);
        $err = curl_error($ch);
        
        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // curl_close($ch);
        
        if ($err) {
            throw new Exception("cURL Error: " . $err);
        }

        $result = [];
        
        $responseData = json_decode($response, true);
        
        if ($httpCode !== 200 || !$responseData['status']) {
            $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Failed to initialize payment';
            throw new Exception($errorMessage);
        }
        
        // Set authorization URL and reference from response
        if (isset($responseData['data']['authorization_url'])) {
            $this->auth_url = $responseData['data']['authorization_url'];
        }
        
        if (isset($responseData['data']['reference'])) {
            $this->reference = $responseData['data']['reference'];
        }

        $this->status = true; // $responseData['data']['status'];

        // dd($payment);
        
        return $this;
    }

    function verify($reference = ''){
        if (empty($reference)) {
            throw new Exception("Reference is required");
        }

        $secretKey = $this->registry->get('paystack_secret_key');

        $url = self::PAYSTACK_BASE_URL . "/transaction/verify/" . urlencode($reference);        
        // Initialize curl
        $ch = curl_init();
        
        // Set curl options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $secretKey,
            "Cache-Control: no-cache",
        ));
        
        // Execute curl request
        $response = curl_exec($ch);
        $err = curl_error($ch);
        
        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // curl_close($ch);
        
        if ($err) {
            // echo "cURL Error: " . $err . "<br>";
            // throw new Exception("cURL Error: " . $err);
            $this->status = false;
            $this->error = $err;
            return $this;
        }
        
        $responseData = json_decode($response, true);

        // dd($responseData);
        
        if ($httpCode !== 200 || !$responseData['status']) {
            $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Failed to verify payment';
            // dd($responseData);
            // echo "Error: " . $errorMessage . "<br>";
            // throw new Exception($errorMessage);
            $this->status = false;
            $this->error = $errorMessage;
            return $this;
        }
        
        // Set payment status from response
        if (isset($responseData['data']['status'])) {
            $this->status = $responseData['data']['status'];
        }

        if (isset($responseData['data']['authorization']['authorization_code'])) {
            $this->authorization_code = $responseData['data']['authorization']['authorization_code'];
        }
        
        return $this;
    }


    /**
     * Authorize a payment
     * @param string $authorization_code
     * @param string $email
     * @param float $amount
     * @return array
     */
    function authorize($authorization_code, $email, $amount){

        $reference = uniqid("PAYSTACK-AUTHORIZE-");  //$payment->reference
        
        $payload = [
            "authorization_code" => $authorization_code,
            "email" => $email,
            "amount" => (int) $amount * 100,
            "reference" => $reference
        ];

        $url = self::PAYSTACK_BASE_URL . '/transaction/charge_authorization';
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->registry->get('paystack_secret_key'),
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            // dd($response, $payload);
            throw new Exception('Failed to charge authorization. HTTP Status Code: ' . $httpCode);
        }

        $this->result = json_decode($response, true);
        if (!$this->result) {
            throw new Exception('Invalid response from Paystack charge authorization API');
        }

        $this->status = true;

        return $this;
    }

    public function getAuthUrl(){
        return $this->auth_url;
    }

    public function getReference(){
        return $this->reference;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getError(){
        return $this->error;
    }

    public function getAuthorizationCode(){
        return $this->authorization_code;
    }

}