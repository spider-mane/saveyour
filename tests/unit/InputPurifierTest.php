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

    public function testCanGetAndSetValidators()
    {
        $purifier = new InputPurifier;

        $rule = 'valid_phone';
        $validator = Validator::phone();
        $rules = [$rule => $validator];

        $purifier->setRules($rules);

        $this->assertCount(1, $purifier->getRules());
        $this->assertEquals($rules, $purifier->getRules());
    }

    public function testCanAddAndGetSingleValidator()
    {
        $purifier = new InputPurifier;

        $rule = 'valid_phone';
        $validator = Validator::phone();

        $purifier->addRule($rule, $validator);

        $this->assertEquals($validator, $purifier->getRule($rule));
    }

    public function testValidatorsAreProperInterface()
    {
        $this->expectException('TypeError');

        $purifier = new InputPurifier;

        $purifier->setRules([
            'pass1' => Validator::phone(),
            'pass2' => Validator::email(),
            'fail1' => 'foobar',
            'fail2' => new DateTime,
            'fail3' => 45645656,
            'fail4' => 1.5,
            'fail5' => false,
        ]);

        $this->assertCount(2, $purifier->getRules());
    }

    public function testProperlyValidatesInput()
    {
        $purifier = new InputPurifier;

        $pass = '202-527-7854';
        $fail = 'foobar';

        $purifier->addRule('valid_phone', Validator::phone());

        $this->assertEquals($pass, $purifier->filterInput($pass));
        $this->assertFalse($purifier->filterInput($fail));
    }

    public function testCanSetAndGetAlerts()
    {
        $purifier = new InputPurifier;

        $alerts = [
            'test1' => 'foo',
            'test2' => 'bar',
        ];

        $purifier->setAlerts($alerts);

        $this->assertEquals($alerts, $purifier->getAlerts());
        $this->assertCount(count($alerts), $purifier->getAlerts());
    }

    public function testCanAddAndGetSingleAlert()
    {
        $purifier = new InputPurifier;

        $rule = 'foo';
        $alert = 'bar';

        $purifier->addAlert($rule, $alert);

        $this->assertEquals($alert, $purifier->getAlert($rule));
    }

    public function testCanGetAndSetAlertsViaRuleSetterMethod()
    {
        $purifier = new InputPurifier;

        $alert1 = 'foo';
        $alert2 = 'bar';

        $rules = [
            'test1' => [
                'validator' => $this->createMock(Validatable::class),
                'alert' => $alert1
            ],
            'test2' => [
                'validator' => $this->createMock(Validatable::class),
                'alert' => $alert2
            ]
        ];

        $purifier->setRules($rules);

        $this->assertCount(count($rules), $purifier->getAlerts());
        $this->assertEquals($alert1, $purifier->getAlert('test1'));
        $this->assertEquals($alert2, $purifier->getAlert('test2'));
    }

    public function testCanAddAlertViaRuleAdderMethod()
    {
        $purifier = new InputPurifier;

        $rule = 'foo';
        $alert = 'bar';

        $purifier->addRule($rule, $this->createMock(Validatable::class), $alert);

        $this->assertEquals($alert, $purifier->getAlert($rule));
    }

    public function testStoresAndProvidesRuleViolations()
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
        $this->assertArrayHasKey('fail', $purifier->getViolations());
    }

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
