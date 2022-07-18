<?php

namespace WebTheory\Saveyour\Processor;

use Closure;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\TextPart;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class FormSubmissionSymfonyMailer extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected Mailer $mailer;

    protected Email $email;

    protected Closure $composer;

    public function __construct(string $name, Mailer $mailer, ?Email $email = null, ?Closure $composer = null, ?array $fields = null)
    {
        if (!$email && !$composer) {
            throw new LogicException(
                'At least one of arguments "$email" or "$composer" must be provided.'
            );
        }

        parent::__construct($name, $fields);

        $this->mailer = $mailer;
        $this->email = $email ?? new Email();
        $this->composer = $composer;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        if (!$this->allFieldsPresent($results)) {
            return null;
        }

        $values = $this->mappedValues($results);
        $message = (isset($this->composer))
            ? ($this->composer)($values, $this->email, $request)
            : $this->replaceValues($values);

        $this->email->setBody(new TextPart($message));

        $this->mailer->send($this->email, $this->envelope);

        return null;
    }

    protected function replaceValues(array $values): string
    {
        $message = $this->email->getBody()->bodyToString();

        foreach ($values as $key => $value) {
            if ($message) {
                $message = str_replace('{{' . $key . '}}', $value, $message);
            } else {
                $message .= sprintf("%s: %s\n", $key, $value);
            }
        }

        return $message;
    }
}
