<?php

use GuzzleHttp\Psr7\ServerRequest;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Controllers\FormFieldControllerBuilder;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

$field1 = new FormFieldControllerBuilder('test-1');
$field2 = new FormFieldControllerBuilder('test-2');
$field3 = new FormFieldControllerBuilder('test-3');

$field3->await('test-1');


$fields = [$field1, $field2, $field3];

usort($fields, function (FormFieldControllerInterface $a, FormFieldControllerInterface $b) {
    if (in_array($a->getRequestVar(), $b->mustAwait())) {
        $thing = -1;

        exit(var_dump($thing));
    }

    if (in_array($b->getRequestVar(), $a->mustAwait())) {
        $thing = 1;

        exit(var_dump($thing));
    }

    $thing = 0;

    return $thing;
});

$request = new ServerRequest('post', 'https://test.test');
$request = $request->withParsedBody([
    'test-1' => '1',
    'test-2' => '2',
    'test-3' => '3',
]);

$manager = new FormSubmissionManager;
$manager->setFields(
    $field1->create(),
    $field2->create(),
    $field3->create()
);

// exit(var_dump(in_array(
//     $field1->create()->getRequestVar(),
//     $field3->create()->mustAwait()
// )));

$results = $manager->process($request);

// exit(var_dump($results));
