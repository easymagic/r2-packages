<?php 
namespace R2Packages\Framework\v2\Interfaces;

interface NotificationInterface
{
    /**
     * Send a notification to a user
     * @param string $to
     * @param string $subject
     * @param string $from
     * @param string $body
     * @return string
     */
    public function send($to,$subject,$from,$body);
}