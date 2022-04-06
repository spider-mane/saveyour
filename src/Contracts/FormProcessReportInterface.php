<?php

namespace WebTheory\Saveyour\Contracts;

interface FormProcessReportInterface
{
    /**
     * @return array<string,string>
     */
    public function results(): array;
}
