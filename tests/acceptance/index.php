<?php


use WebTheory\Saveyour\Contracts\ChecklistItemsInterface;
use WebTheory\Saveyour\Contracts\RadioGroupSelectionInterface;
use WebTheory\Saveyour\Contracts\SelectOptionsProviderInterface;
use WebTheory\Saveyour\Elements\OptGroup;
use WebTheory\Saveyour\Fields\Checklist;
use WebTheory\Saveyour\Fields\RadioGroup;
use WebTheory\Saveyour\Fields\Select;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

################################################################################
# bootstrap
################################################################################

# composer autoload
require "../../vendor/autoload.php";

# filp/whoops error handling
(new Run)->prependHandler(new PrettyPageHandler)->register();

include '../env.php';

################################################################################
# start
################################################################################
echo "<h1>Visual Field Tests</h1><hr>";

################################################################################
# Radio Selection
################################################################################
echo '<h2>RadioGroup Test</h2>';

$provider = new class implements RadioGroupSelectionInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-1',
                'label' => 'Test 1',
                'id' => 'radio-selection-test-1'
            ],
            [
                'value' => 'test-2',
                'label' => 'Test 2',
                'id' => 'radio-selection-test-2'
            ]
        ];
    }

    public function provideItemValue($item): string
    {
        return $item['value'];
    }

    public function provideItemId($item): string
    {
        return $item['id'];
    }

    public function provideItemLabel($item): string
    {
        return $item['label'];
    }
};

$selection = new RadioGroup();
$selection->setSelectionProvider($provider);
$selection->setLabelOptions([
    'classlist' => ['label-class', 'label-class-1']
]);
$selection->setName('radio-group-test-1');
$selection->setValue('test-2');

echo '<h3>Inline</h3>';
echo $selection;

$selection->setIsInline(false);
$selection->setName('radio-group-test-2');

echo '<h3>Not Inline</h3>';
echo $selection;

echo '<hr>';

################################################################################
# Checklist
################################################################################
echo '<h2>Checklist Test</h2>';

$provider = new class implements ChecklistItemsInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-1',
                'label' => 'Test 1',
                'id' => 'checklist-test-1'
            ],
            [
                'value' => 'test-2',
                'label' => 'Test 2',
                'id' => 'checklist-test-2'
            ]
        ];
    }

    public function provideItemValue($item): string
    {
        return $item['value'];
    }

    public function provideItemId($item): string
    {
        return $item['id'];
    }

    public function provideItemLabel($item): string
    {
        return $item['label'];
    }
};

$checklist = new Checklist;
$checklist->setSelectionProvider($provider);
$checklist->setValue('test-1');
$checklist->setName('checklist-test-1');

echo $checklist->toHtml();
echo '<hr>';

################################################################################
# Select
################################################################################
echo '<h2>Select Test</h2>';

$select = new Select();

$provider1 = new class implements SelectOptionsProviderInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-A',
                'text' => 'Test A',
            ],
            [
                'value' => 'test-B',
                'text' => 'Test B',
            ]
        ];
    }

    public function provideItemValue($item): string
    {
        return $item['value'];
    }

    public function provideItemText($item): string
    {
        return $item['text'];
    }
};

$select->setPlaceholder('Select Test');
$select->setSelectionProvider($provider1);
$select->setValue('test-B');

echo '<h3>Single Value</h3>';
echo $select->toHtml();

echo str_repeat('<br>', 2);

echo '<h3>Multi-Value with optgroups and Size >1</h3>';

$provider2 = new class implements SelectOptionsProviderInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-C',
                'text' => 'Test C',
            ],
            [
                'value' => 'test-D',
                'text' => 'Test D',
            ]
        ];
    }

    public function provideItemValue($item): string
    {
        return $item['value'];
    }

    public function provideItemText($item): string
    {
        return $item['text'];
    }
};

$provider3 = new class implements SelectOptionsProviderInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-E',
                'text' => 'Test E',
            ],
            [
                'value' => 'test-F',
                'text' => 'Test F',
            ]
        ];
    }

    public function provideItemValue($item): string
    {
        return $item['value'];
    }

    public function provideItemText($item): string
    {
        return $item['text'];
    }
};

$optgroup1 = new OptGroup('Test Group 1');
$optgroup2 = new OptGroup('Test Group 2');
$optgroup3 = new OptGroup('Test Group 3');

$optgroup1->setSelectionProvider($provider1);
$optgroup2->setSelectionProvider($provider2);
$optgroup3->setSelectionProvider($provider3);

$select->setGroups($optgroup1, $optgroup2, $optgroup3);
$select->setSize(5);
$select->setMultiple(true);
$select->setValue(['test-C', 'test-F']);

echo $select->toHtml();
echo '<hr>';
