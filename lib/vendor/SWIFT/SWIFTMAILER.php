<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SWIFT;

class SWIFTMAILER {

    private static $instance;
    private $mailer_host;
    private $maler_user;
    private $mailer_password;
    private $mailer_port;
    private $from;
    private $from_name;
    private $template_dir;

    /** @var \Swift_SmtpTransport */
    private $transport;

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var \Swift_Mime_ContentEncoder */
    private $encoder;
    protected $attachments;

    protected function preprocess_styles($message_text) {
        $document = \PHPQuery\PhpQ::D($message_text);
        $stylebuilder = \PHPQuery\styleparser::F();
        foreach ($document->find('style') as $styletag) {
            $stylebuilder->append(pq($styletag)->text());
        }
        $stylebuilder->sort_rules();
        $rules = $stylebuilder->get_rules();

        $dtr = [];
        foreach ($rules as $selector => $style) {
            $search = $document->find($selector);
            $dtr[] = "find:{$selector}, found:{$search->length}\n";
            foreach ($search as $found) {
                $pfound = pq($found);
                $pfound->attr('style', $style->merge_attributed_style((string) $pfound->attr('style')));
            }
        }
        $document->charset = "utf-8";
        $r = (string) $document->html();
        return $r;
    }

    public function inline_img($filename, $mime = 'application/octet-stream') {
        $path = "{$filename}";
        if (file_exists($path) && is_file($path) && is_readable($path)) {
            $name = pathinfo($path, PATHINFO_BASENAME);
            $image = \Swift_Image::fromPath($path);
            $image->setFilename($name);
            $image->setContentType($mime);
            $image->generateId();
            $image->setDisposition('inline');
            $this->attachments[] = $image;
            //$mo->embed($att);
            return $image->getId();
        }
        return '';
    }

    /**
     * 
     * @param type $template
     * @param string $to   strict!
     * @param type $theme
     * @param type $data
     * @param array $attachments
     */
    public function send_email_with_template(string $template, string $to, string $theme, array $data, array $attachments = null, \AsyncTask\AsyncTaskAbstract $task = null) {
        $smarty = \smarty\SMW::F()->smarty;
        $smarty->assign('subject', $theme);
        $this->attachments = [];

        $smarty->assign('wrapper_class', null);
        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }
        $smarty->assign('this', $this);
        $template_path = \Config\Config::F()->VIEW_PATH . "mailer" . DIRECTORY_SEPARATOR;
        if (!file_exists($template_path)) {
            @mkdir($template_path, 0777, true);
        }
        $this->template_dir = rtrim("{$template_path}{$template}", DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $template_name = "{$this->template_dir}index.tpl";
        $message_text = $smarty->fetch($template_name);
        $message_text = $this->preprocess_styles($message_text);
        $message = \Swift_Message::newInstance();
        $message->setCharset("utf-8");
        $message->setTo($to);
        $message->setFrom($this->from, $this->from_name);
        $message->setSubject($theme);
        $message->setEncoder($this->encoder);
        $message->setBody($message_text, 'text/html', 'utf-8');
        if (!count($this->attachments)) {
            if ($task) {
                $task->log("no attachments");
            }
        }
        foreach ($this->attachments as $attachment) {
            if ($attachment instanceof \Swift_Mime_Attachment) {/* @var $attachment \Swift_Mime_Attachment */
                $message->attach($attachment);
                if ($task) {
                    $task->log('attachment_dump', 'info');
                    $task->log(print_r($attachment->getFilename(), true), 'info');
                }
                \Swift_Image::LEVEL_ALTERNATIVE;
            } else if ($task) {
                $task->log("not attachment class " . get_class($attachment));
            }
        }
        if ($attachments && is_array($attachments) && count($attachments)) {
            foreach ($attachments as $attachment) {
                if ($attachment instanceof \Swift_Mime_Attachment) {/* @var $attachment \Swift_Mime_Attachment */
                    $message->attach($attachment);
                    if ($task) {
                        $task->log('attachment_dump', 'info');
                        $task->log(print_r($attachment->getFilename(), true), 'info');
                    }
                }
            }
        }
        $this->mailer->send($message);
    }

    protected function __construct() {
        static::$instance = $this;
        require_once __DIR__ . DIRECTORY_SEPARATOR . "swift_required.php";
        $PM = \PresetManager\PresetManager::F();
        $this->mailer_host = $PM->get_filtered("MAILER_SMTP_HOST", ["Trim", "NEString", 'DefaultNull']);
        $this->mailer_port = $PM->get_filtered("MAILER_SMTP_PORT", ["IntMore0", "DefaultNull"]);
        $this->maler_user = $PM->get_filtered("MAILER_SMTP_USER", ["Trim", "NEString", "DefaultNull"]);
        $this->mailer_password = $PM->get_filtered_def("MAILER_SMTP_PASSWORD", ["Trim", "NEString", 'DefaultNull']);
        if ($this->mailer_port === 25) {
            $this->transport = \Swift_SmtpTransport::newInstance($this->mailer_host, $this->mailer_port);
        } else {
            $this->transport = \Swift_SmtpTransport::newInstance($this->mailer_host, $this->mailer_port, 'ssl');
        }
        $this->transport->setUsername($this->maler_user);
        $this->transport->setPassword($this->mailer_password);
        $this->mailer = \Swift_Mailer::newInstance($this->transport);
        $this->encoder = new \Swift_Mime_ContentEncoder_Base64ContentEncoder();
        $this->from = $PM->get_filtered("MAILER_FROM", ["Trim", "NEString", "EmailMatch", "DefaultNull"]);
        $this->from_name = $PM->get_filtered("MAILER_FROM_NAME", ["Trim", "NEString", "DefaultNull"]);
    }

    /**
     * 
     * @return SWIFTMAILER
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
