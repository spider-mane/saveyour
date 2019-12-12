<?php

namespace WebTheory\Saveyour\Processors;

use Psr\Http\Message\ServerRequestInterface;
use Swift_Mailer;
use Swift_Message;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;

class FormDataSwiftMailer extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Swift_Message
     */
    protected $message;

    /**
     *
     */
    public function __construct(Swift_Mailer $mailer, Swift_Message $message)
    {
        $this->mailer = $mailer;
        $this->message = $message;
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface
    {
        $values = $this->extractValues($results);
        $message = '';

        foreach ($values as $key => $value) {
            $message .= "{$key}: {$value}\n";
        }

        $this->mailer->send($this->message->setBody($message));

        return null;
    }
}
