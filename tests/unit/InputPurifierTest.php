<?php

use PHPUnit\Framework\TestCase;
use Respect\Validation\Validatable;
use Respect\Validation\Validator;
use WebTheory\Saveyour\InputPurifier;

class InputPurifierTest extends TestCase
{
    public function testCanGetAndSetFilters()
    {
        $purifier = new InputPurifier;
        $filters = ['htmlspecialchars', 'strtolower'];

        $purifier->setFilters(...$filters);

        $this->assertEquals($filters, $purifier->getFilters());
    }

    public function testCanAddSingleFilter()
    {
        $purifier = new InputPurifier;

        $filter = 'strtolower';

        $purifier->addFilter($filter);

        $this->assertCount(1, $purifier->getFilters());
        $this->assertContains($filter, $purifier->getFilters());
    }

    public function testFiltersMustBeCallable()
    {
        $this->expectException('TypeError');

        $purifier = new InputPurifier;

        $valid = ['htmlspecialchars', 'strtolower'];
        $invalid = ['gsafaf', 4, true];

        $purifier->setFilters(...$valid + $invalid);

        $this->assertEquals(count($valid), $purifier->getFilters());
    }

    public function testProperlySanitizesInput()
    {
        $purifier = new InputPurifier;
        $filters = ['strtoupper', 'trim'];

        $unfiltered = '   foO               ';
        $expected = 'FOO';

        $purifier->setFilters(...$filters);

        $this->assertEquals($expected, $purifier->filterInput($unfiltered));
    }

    public function testSanitizesMultipleInputs()
    {
        $purifier = new InputPurifier;
        $filters = ['strtoupper', 'trim'];

        $unfiltered = ['foo  ', '   Bar       '];
        $expected = ['FOO', 'BAR'];

        $purifier->setFilters(...$filters);

        $this->assertEquals($expected, $purifier->filterInput($unfiltered));
    }

    public function testCanGetAndSetValidator()
    {
        $purifier = new InputPurifier;
        $validator = Validator::phone();

        $purifier->setValidator($validator);

        $this->assertEquals($validator, $purifier->getValidator());
    }

    public function testValidatorsAreProperInterface()
    {
        $this->expectException('TypeError');

        $purifier = new InputPurifier;

        $purifier->setValidator('foobar');
        $this->assertCount(0, $purifier->getRules());

        $purifier->setValidator(new DateTime);
        $this->assertCount(0, $purifier->getRules());

        $purifier->setValidator(1654984);
        $this->assertCount(0, $purifier->getRules());

        $purifier->setValidator(15.54946);
        $this->assertCount(0, $purifier->getRules());

        $purifier->setValidator(false);
        $this->assertCount(0, $purifier->getRules());
    }

    public function testProperlyValidatesInput()
    {
        $purifier = new InputPurifier;

        $pass = '202-527-7854';
        $fail = 'foobar';

        $purifier->setValidator(Validator::phone());

        $this->assertEquals($pass, $purifier->filterInput($pass));
        $this->assertFalse($purifier->filterInput($fail));
    }

    public function testCollectsAlertsOnValidationFailure()
    {
        $purifier = new InputPurifier;
        $template = '{{input}} is an invalid {{name}}';
        $input = 'foobar';

        $v1 = Validator::phone()->setName('Phone Number')->setTemplate($template);
        $v2 = Validator::email()->setName('Email Address')->setTemplate($template);

        $validator = Validator::allOf($v1, $v2);

        $alerts = [
            'Phone Number' => "{$input} is an invalid {$v1->getName()}",
            'Email Address' => "{$input} is an invalid {$v2->getName()}",
        ];

        $purifier->setValidator($validator);

        $purifier->filterInput($input);

        $this->assertEquals($alerts, $purifier->getAlerts());
        $this->assertCount(count($alerts), $purifier->getAlerts());
    }

    // public function testCanGetAndSetAlertsViaRuleSetterMethod()
    // {
    //     $purifier = new InputPurifier;

    //     $alert1 = 'foo';
    //     $alert2 = 'bar';

    //     $rules = [
    //         'test1' => [
    //             'validator' => $this->createMock(Validatable::class),
    //             'alert' => $alert1
    //         ],
    //         'test2' => [
    //             'validator' => $this->createMock(Validatable::class),
    //             'alert' => $alert2
    //         ]
    //     ];

    //     $purifier->setRules($rules);

    //     $this->assertCount(count($rules), $purifier->getAlerts());
    //     $this->assertEquals($alert1, $purifier->getAlert('test1'));
    //     $this->assertEquals($alert2, $purifier->getAlert('test2'));
    // }

    // public function testCanAddAlertViaRuleAdderMethod()
    // {
    //     $purifier = new InputPurifier;

    //     $rule = 'foo';
    //     $alert = 'bar';

    //     $purifier->addRule($rule, $this->createMock(Validatable::class), $alert);

    //     $this->assertEquals($alert, $purifier->getAlert($rule));
    // }

    // public function testStoresAndProvidesRuleViolations()
    // {
    //     $purifier = new InputPurifier;

    //     $test1 = 'test@test.com';

    //     $rules = [
    //         'pass' => Validator::email(),
    //         'fail' => Validator::phone(),
    //     ];

    //     $purifier->setRules($rules);
    //     $purifier->filterInput($test1);

    //     $this->assertCount(1, $purifier->getViolations());
    //     $this->assertArrayHasKey('fail', $purifier->getViolations());
    // }

    public function testCanClearRuleViolations()
    {
        $purifier = new InputPurifier;

        $test1 = 'test@test.com';

        $rules = [
            'pass' => Validator::email(),
            'fail' => Validator::phone(),
        ];

        $purifier->setRules($rules);
        $purifier->filterInput($test1);

        $this->assertCount(1, $purifier->getViolations());

        $purifier->clearViolations();

        $this->assertCount(0, $purifier->getViolations());
    }

    public function testViolationsContainAlert()
    {
        $purifier = new InputPurifier;

        $test1 = 'test@test.com';

        $rules = [
            'pass' => Validator::email(),
            'fail' => Validator::phone(),
        ];

        $failAlert = 'foobar';

        $purifier->setRules($rules);
        $purifier->setAlerts(['fail' => $failAlert]);
        $purifier->filterInput($test1);

        $this->assertContains($failAlert, $purifier->getViolations());
    }
}
