<?php 
namespace R2Packages\Framework\Application\Mail;

interface MailServiceInterface
{
    public function send(string $to, string $subject, string $from, string $body);
}