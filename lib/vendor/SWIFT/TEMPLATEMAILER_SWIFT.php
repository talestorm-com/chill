<?php

require_once(__DIR__ . '/../../lib/SWIFT/swift_required.php');

class TEMPLATEMAILER_SWIFT {

    protected static $mimes = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
        'pdf' => 'application/pdf']; 

    
    protected static function WL($to,$subj,array $data=[]){
      //  $fn = __DIR__.DIRECTORY_SEPARATOR."mailer.log";
      //  $f = fopen($fn, "a+b");
      //  $d= new DateTime();
      //  fputs($f, "{$d->format('d.m.Y H.i.s')} " .print_r($to,true)." ".print_r($subj,true)." ".print_r($data,true)."\n======================\n");
      //  fclose($f);
    }
    
    public static function MAILMAIL($template, $to, $from, $subj, $reply_to = false, array $data = []) {
        static::WL($to, $subj);
        $TPLDIR = '';
        $vp = rtrim(Yii::getPathOfAlias('application.views'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'SwiftMailTemplates' . DIRECTORY_SEPARATOR;
        $ttd = $vp . $template;
        if (file_exists($ttd) && is_dir($ttd) && is_readable($ttd)) {
            $TPLDIR = $ttd;
        } else {
            $ttd = $vp . 'default';
            if (file_exists($ttd) && is_dir($ttd) && is_readable($ttd)) {
                $TPLDIR = $ttd;
            } else {
                return false;
            }
        }
        if ($TPLDIR == '') {
            return false;
        }
        $data['TPLDIR'] = $TPLDIR;
        $mo = Swift_Message::newInstance();
        $message = static::render($TPLDIR, $data, $mo); 
        $mo->setTo($to);
        $mo->setFrom($from);
        $mo->setSubject($subj);
        if (array_key_exists('b_carbon_copy', $data)) {
            if (is_array($data['b_carbon_copy'])) {
                foreach ($data['b_carbon_copy'] as $ml) { 
                    if (mb_strlen(trim($ml), 'UTF-8')) {
                        $mo->addBcc($ml);
                    }
                }
            } else if (is_string($data['b_carbon_copy']) && mb_strlen(trim($data['b_carbon_copy']), 'UTF-8')) {
                $mo->addBcc($data['b_carbon_copy']);
            }
        }
        if ($reply_to) {
            $mo->setReplyTo($reply_to);
        }
        $mo->setEncoder(new Swift_Mime_ContentEncoder_Base64ContentEncoder());
        $mo->setBody($message, 'text/html', 'UTF-8');
        static::autoincludes($TPLDIR, $mo);
        if (array_key_exists('ATTACHMENTS', $data) && is_array($data['ATTACHMENTS']) && count($data['ATTACHMENTS'])) {
            foreach ($data['ATTACHMENTS'] as $afn => $adata) {
                if (is_array($adata) && array_key_exists('data', $adata) && array_key_exists('mime', $adata)) {
                    $cnt = Swift_Attachment::newInstance($adata['data'], $afn, $adata['mime']);
                    $cnt->setId(str_ireplace('.', '@', $afn));
                    $mo->attach($cnt);
                }
            }
        }
        //$mo->setEncoder(new Swift_Mime_ContentEncoder_Base64ContentEncoder());
        $host = SitePref2::G('MAILER_MODULE_SMTP_HOST', null);
        $port = SitePref2::G('MAILER_MODULE_SMTP_PORT', null);
        if (!$host || !$port) {
            return;
        }
        $user = SitePref2::G('MAILER_MODULE_SMTP_USER', '');
        $password = SitePref2::G('MAILER_MODULE_SMTP_PASSWD', '');
        $transport = Swift_SmtpTransport::newInstance($host, $port);
        $transport->setUsername($user);
        $transport->setPassword($password);
        $mailer = Swift_Mailer::newInstance($transport);
        try {
            $mailer->send($mo);
        } catch (Exception $e) {
            static::WL("error", $e->getMessage());
        }
        return true;
    }

    protected static function render($dir, $data, $MAILER) {
        extract($data);
        ob_start();
        $__vp = rtrim(Yii::getPathOfAlias('application.views'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'SwiftMailTemplates' . DIRECTORY_SEPARATOR;
        if (file_exists($dir . DIRECTORY_SEPARATOR . 'header.php')) {
            include $dir . DIRECTORY_SEPARATOR . 'header.php';
        } else {
            include $__vp . 'common' . DIRECTORY_SEPARATOR . 'header.php';
        }
        include $dir . DIRECTORY_SEPARATOR . 'index.php';
        if (file_exists($dir . DIRECTORY_SEPARATOR . 'footer.php')) {
            include $dir . DIRECTORY_SEPARATOR . 'footer.php';
        } else {
            include $__vp . 'common' . DIRECTORY_SEPARATOR . 'footer.php';
        }
        return static::preprocess_styles(ob_get_clean());
    }
    
    protected static function preprocess_styles($message_text){
        \phpQuery\phpQueryLib::F();
        $document = phpQuery::newDocumentHTML($message_text) ;
        
        $stylebuilder = \phpQuery\styleparser::F();
        foreach($document->find('style') as $styletag){
            
            $stylebuilder->append(pq($styletag)->text());
        }
        $stylebuilder->sort_rules();
        $rules = $stylebuilder->get_rules();
        
        $dtr = [];
        foreach ($rules as $selector=>$style){
            $search = $document->find($selector);
            $dtr[]="find:{$selector}, found:{$search->length}\n";
            foreach($search as $found){
                $pfound = pq($found);
                $pfound->attr('style', $style->merge_attributed_style((string)$pfound->attr('style')));
            }
        }
        $r = (string)$document->html();
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR."mailer_result.dump", $r);
        
        return $r;
    }

    protected static function autoincludes($dir, Swift_Message $mo) {
        $dir .= DIRECTORY_SEPARATOR . 'autoinclude';
        if (file_exists($dir) && is_dir($dir) && is_readable($dir)) {
            $h = opendir($dir);
            while (($a = readdir($h)) !== false) {
                $fp = $dir . DIRECTORY_SEPARATOR . $a;
                if (!is_dir($fp) && is_readable($fp)) {
                    $pi = pathinfo($a);
                    $ext = mb_strtolower($pi['extension'], 'UTF-8');
                    if (array_key_exists($ext, static::$mimes)) {
                        $att = Swift_Image::fromPath($fp);
                        $att->setFilename($a);
                        $att->setId(str_ireplace('.', '@', $a));
                        $att->setDisposition('inline');
                        $mo->embed($att);
                    }
                }
            }
            closedir($h);
        }
    }

    /**
     * фуфельный метод для загрузки зависимости
     * @return int
     */
    public static function EPT() {
        return 1;
    }

}

?>