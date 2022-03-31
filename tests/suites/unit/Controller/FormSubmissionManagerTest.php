<?php

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;
use WebTheory\Saveyour\Controllers\FieldOperationCacheBuilder;
use WebTheory\Saveyour\Controllers\FormFieldController;
use WebTheory\Saveyour\Controllers\FormFieldControllerBuilder;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

class FormSubmissionManagerTest extends TestCase
{
    /**
     * @var FormSubmissionManager
     */
    public $manager;

    /**
     * @var ServerRequestPolicyInterface
     */
    public $stubPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubPolicy = $this->createStub(ServerRequestPolicyInterface::class);
        $this->manager = new FormSubmissionManager();
    }

    /**
     * @test
     */
    public function it_processes_form() {
        $manager = $this->manager;

        $manager->set
    }
}
