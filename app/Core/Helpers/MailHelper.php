<?php

namespace App\Core\Helpers;

use App\Exceptions\MailException;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailHelper
{
    public $subject;

    public $from;

    public $to;

    public $bcc;

    public $body;

    public $attachment = null;

    public $filename;

    public $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->to = array();
        $this->bcc = array();
    }

    /**
     * Set a recipient
     *
     * @param string $email
     */
    public function to(string $email)
    {
        array_push($this->to, $email);
    }

    /**
     * Set a hidden copy recipient
     *
     * @param string $email
     */
    public function setBcc(string $email)
    {
        array_push($this->bcc, $email);
    }

    /**
     * Set the email subject
     *
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set the email body
     *
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * Set the email body
     *
     * @param string $body
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Set the attachment
     *
     * @param string $attachment
     */
    public function setAttachment(string $attachment, string $filename)
    {
        $this->attachment = $attachment;
        $this->filename = $filename;
    }

    /**
     * Send the email
     *
     * @throws MailException
     * @return int
     */
    public function send(): int
    {
        try {
            $transport = (new Swift_SmtpTransport($this->settings['host'], $this->settings['port']))
                ->setUsername($this->settings['username'])
                ->setPassword($this->settings['password']);
            $transport->setEncryption($this->settings['encryption']);
            $mailer = new Swift_Mailer($transport);
            $message = (new Swift_Message($this->subject))
                ->setFrom($this->settings["from"])
                ->setTo($this->to)
                ->setBcc($this->bcc)
                ->setPriority(1);

            if ($this->attachment != null) {
                $message->attach(new Swift_Attachment(
                    $this->attachment,
                    $this->filename . '.pdf',
                    'application/pdf'
                ));
            } else {
                $message->setBody($this->body, "text/html");
            }

            $this->to = array();
            $this->bcc = array();
            return $mailer->send($message);
        } catch (\Exception $ex) {
            throw new MailException($ex->getMessage(), 500);
        }
    }
}
