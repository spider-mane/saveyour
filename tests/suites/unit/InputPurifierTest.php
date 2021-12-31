<?php

use Tests\Support\TestCase;
use Respect\Validation\Validatable;
use Respect\Validation\Validator;
use WebTheory\Saveyour\InputPurifier;

class InputPurifierTest extends TestCase
{
    public function testCanGetAndSetFilters()
    {
        $filters = ['htmlspecialchars', 'strtolower'];
        $purifier = new InputPurifier(null, ...$filters);

        $this->assertEquals($filters, $purifier->getFilters());
    }

    public function testCanAddSingleFilter()
    {
        $filter = 'strtolower';
        $purifier = new InputPurifier(null, $filter);

        $this->assertCount(1, $purifier->getFilters());
        $this->assertContains($filter, $purifier->getFilters());
    }

    public function testFiltersMustBeCallable()
    {
        $this->expectException('TypeError');

        $valid = ['htmlspecialchars', 'strtolower'];
        $invalid = ['gsafaf', 4, true];

        $purifier = new InputPurifier(null, ...array_merge($valid, $invalid));

        $this->assertEquals(count($valid), $purifier->getFilters());
    }

    public function testProperlySanitizesInput()
    {
        $filters = ['strtoupper', 'trim'];
        $purifier = new InputPurifier(null, ...$filters);

        $unfiltered = '   foO               ';
        $expected = 'FOO';

        $this->assertEquals($expected, $purifier->filterInput($unfiltered));
    }

    public function testSanitizesMultipleInputs()
    {
        $filters = ['strtoupper', 'trim'];
        $purifier = new InputPurifier(null, ...$filters);

        $unfiltered = ['foo  ', '   Bar       '];
        $expected = ['FOO', 'BAR'];

        $this->assertEquals($expected, $purifier->filterInput($unfiltered));
    }

    public function testCanGetAndSetValidator()
    {
        $validator = Validator::phone();
        $purifier = new InputPurifier($validator);

        $this->assertEquals($validator, $purifier->getValidator());
    }

    public function testProperlyValidatesInput()
    {
        $purifier = new InputPurifier(Validator::phone());

        $pass = '202-527-7854';
        $fail = 'foobar';

        $this->assertEquals($pass, $purifier->filterInput($pass));
        $this->assertFalse($purifier->filterInput($fail));
    }

    public function testCollectsAlertsOnValidationFailure()
    {
        $template = '{{input}} is an invalid {{name}}';
        $input = 'foobar';
        $formatted = "\"$input\"";

        $v1 = Validator::phone()->setName('Phone Number')->setTemplate($template);
        $v2 = Validator::email()->setName('Email Address')->setTemplate($template);

        $v1 = Validator::phone()->setName('Phone Number');
        $v2 = Validator::email()->setName('Email Address');

        $validator = Validator::allOf($v1, $v2)->setName('Test')->setTemplate($template);

        $alerts = [
            'Test' => [
                'Phone Number' => "{$formatted} is an invalid {$v1->getName()}",
                'Email Address' => "{$formatted} is an invalid {$v2->getName()}",
            ]
        ];

        $purifier = new InputPurifier($validator);

        $purifier->filterInput($input);

        $this->assertEquals($alerts, $purifier->getAlerts());
        $this->assertCount(count($alerts), $purifier->getAlerts());
    }
}
