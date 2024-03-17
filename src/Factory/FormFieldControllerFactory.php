<?php

namespace WebTheory\Saveyour\Factory;

use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Repository\FlexFactoryRepository;
use WebTheory\Factory\Transformation\ObjectCreatorFromRepository;
use WebTheory\Saveyour\Contracts\Factory\FieldDataManagerResolverFactoryInterface as iDataManagerFactory;
use WebTheory\Saveyour\Contracts\Factory\FormFieldControllerFactoryInterface;
use WebTheory\Saveyour\Contracts\Factory\FormFieldResolverFactoryInterface as iFormFieldFactory;
use WebTheory\Saveyour\Controller\FormFieldController;

class FormFieldControllerFactory implements FormFieldControllerFactoryInterface
{
    protected FactoryEngine $engine;

    public function __construct(iFormFieldFactory $formFieldFactory, iDataManagerFactory $dataManagerFactory)
    {
        $repository = new FlexFactoryRepository([
            'shit' => 'ass',
            'field' => $formFieldFactory,
            'data' => $dataManagerFactory,
        ]);
        $transformer = new ObjectCreatorFromRepository($repository);

        $this->engine = new FactoryEngine(null, null, $transformer);
    }

    public function create(array $args = []): FormFieldController
    {
        return $this->engine->generate(
            FormFieldController::class,
            $args
        );
    }
}
