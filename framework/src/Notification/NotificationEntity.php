<?php 

namespace R2Packages\Framework\Notification;


use Exception;

class NotificationEntity{


    public $id = 0;
    public $user_id = 0;
    public $type = 'email'; // 'email','sms','push'
    public $title = '';
    public $read_status = 'unread'; // 'unread','read'
    public $intent = 'order'; // 'order','wallet','topup-approvals','order-threads'
    public $message = '';
    public $created_at = '';

    public function __construct($attributes = []){
        setAttributes($this, $attributes);
    }

    function isEmpty(){
        return empty($this->id);
    }

    function newInstance($data = []){
        return new NotificationEntity($data);
    }


    // function validateCreate(){
    //     if(empty($this->user_id)){
    //         throw new Exception('User ID is required!');
    //     }
    //     if(empty($this->title)){
    //         throw new Exception('Title is required!');
    //     }
    //     if(empty($this->message)){
    //         throw new Exception('Message is required!');
    //     }
    //     if(empty($this->intent)){
    //         throw new Exception('Intent is required!');
    //     }

    //     $this->read_status = 'unread';
    // }


}