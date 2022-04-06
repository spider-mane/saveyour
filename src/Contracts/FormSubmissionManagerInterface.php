<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormSubmissionManagerInterface
{
    /**
     * Process the request and return the result of the form submission.
     */
    public function process(ServerRequestInterface $request): ProcessedFormReportInterface;

    /**
     * Verify that the request meets criteria set for processing.
     */
    public function verify(ServerRequestInterface $request): bool;

    /**
     * Check that all fields are valid.
     */
    public function validate(ServerRequestInterface $request): bool;

    /**
     * Get an associative array of of successfully validated fields.
     *
     * @return array<string,string>
     */
    public function validated(ServerRequestInterface $request): array;

    /**
     * Get an associative array of sanitized/processed fields that passed
     * validation requirements.
     *
     * @return array<string,mixed>
     */
    public function processed(ServerRequestInterface $request): array;
}
