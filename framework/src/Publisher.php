<?php 

namespace R2Packages\Framework;

class Publisher
{




    public function publish()
    {
        ob_start();
        $file = __DIR__ . '/mail_templates/mail_template.mail.php';
        $hnd = fopen($file, 'r');
        $content = fread($hnd, filesize($file));
        fclose($hnd);
        return $content;
    }
}