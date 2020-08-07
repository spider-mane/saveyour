<?php

use WebTheory\Html\Html;
use WebTheory\Saveyour\Contracts\ChecklistItemsInterface;
use WebTheory\Saveyour\Contracts\RadioGroupSelectionInterface;
use WebTheory\Saveyour\Contracts\SelectOptionsProviderInterface;
use WebTheory\Saveyour\Elements\OptGroup;
use WebTheory\Saveyour\Fields\Checklist;
use WebTheory\Saveyour\Fields\RadioGroup;
use WebTheory\Saveyour\Fields\Select2;
use WebTheory\Saveyour\Fields\Select;
use WebTheory\Saveyour\Fields\Submit;
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
if ($_POST) exit(var_dump($_POST));
echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <script src="assets/index.js"></script>
</head>
<body>
';

echo "<h1>Visual Field Tests</h1><hr>";
echo Html::open('form', ['method' => 'post']);
echo new Submit;

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
$select->setName('select-test-1');
// $select->setValue('test-B');

echo '<h3>Single Value</h3>';
echo $select->toHtml();

echo str_repeat('<br>', 2);

echo '<h3>Multi-Value with optgroups</h3>';

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
// $select->setSize(5);
$select->setMultiple(true);
$select->setName('select-test-2');
$select->setValue(['test-C', 'test-F']);

dump($select->toHtml());
echo $select->toHtml();
echo '<hr>';

################################################################################
# Select2
################################################################################
echo Html::tag('h2', 'Select2 Test');

$select2 = new Select2;
$select2->setName('select2-test-1');
$select2->setSelectionProvider($provider1);
$select2->setPlaceholder('Select2 Test Placeholder');
$select2->setWidth('200px');

echo $select2->toHtml();
echo str_repeat('<br>', 2);

$select2 = new Select2;
$select2->setName('select2-test-2');
$select2->setSelectionProvider($provider2);
$select2->setPlaceholder('Select2 Test 2 Placeholder');
$select2->setWidth('300px');
$select2->setMultiple(true);

echo $select2->toHtml();
echo str_repeat('<br>', 2);

echo '<hr>';

################################################################################
# end
################################################################################
echo Html::close('form');
echo '</body></html>';
