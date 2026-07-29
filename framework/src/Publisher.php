<?php 

namespace R2Packages\Framework;

class Publisher
{


    function getTemplate($templateName){
        
        $file = __DIR__ . '/' . $templateName . '.php';
        $hnd = fopen($file, 'r');
        $content = fread($hnd, filesize($file));
        fclose($hnd);
        return $content;
    }


    public function publish($template)
    {
        return $this->getTemplate($template);
    }

    public function write($template, $content)
    {
        $file = $template;
        $hnd = fopen($file, 'w');
        fwrite($hnd, $content);
        fclose($hnd);
    }
}