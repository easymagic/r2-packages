<?php

namespace R2Packages\Framework;

class MailService
{
    public $from = 'noreply@example.com';
    
    function send($to, $subject, $from, $template, $data)
    {
        $body = mail_template(MAIL_TEMPLATE_DIR . '/' . $template . '.php', $data);

        if (mail_service() === 'mail') {
            // use normal mail function to send the email as html
            mail($to, $subject, $body, "Content-Type: text/html; charset=UTF-8", $from);
        } else {
            // use mailtrap
            $this->sendWithMailtrap($to, $subject, $from, $body);
        }
        return $body;
    }

    function sendWithMailtrap($to, $subject, $from, $body)
    {
        $url = 'https://sandbox.api.mailtrap.io/api/send/1716409';
        $apiToken = 'f71d7955a233199ac504767489f3eefc'; // Replace with your actual Mailtrap API token

        $postData = [
            'from' => ['email' => $from, 'name' => 'Mailtrap Test'],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'html' => $body,
            'category' => 'Integration Test'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);
        // echo $response;
        // exit;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $response;
    }
}
