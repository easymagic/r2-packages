<?php 

namespace R2Packages\Framework;

class Publisher
{
    public function publish()
    {
        ob_start();
        include __DIR__ . '/mail_templates/mail_template.mail.php';
        $content = ob_get_clean();
        return $content;
    }
}