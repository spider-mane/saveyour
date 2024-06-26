<?php

use WebTheory\Html\Html;
use WebTheory\Saveyour\Contracts\Field\Selection\ChecklistItemsProviderInterface;
use WebTheory\Saveyour\Contracts\Field\Selection\OptionsProviderInterface;
use WebTheory\Saveyour\Contracts\Field\Selection\RadioGroupSelectionInterface;
use WebTheory\Saveyour\Field\Element\OptGroup;
use WebTheory\Saveyour\Field\Selection\StateSelectOptions;
use WebTheory\Saveyour\Field\Type\Checklist;
use WebTheory\Saveyour\Field\Type\RadioGroup;
use WebTheory\Saveyour\Field\Type\Select;
use WebTheory\Saveyour\Field\Type\Select2;
use WebTheory\Saveyour\Field\Type\Submit;
use WebTheory\Saveyour\Field\Type\TrixEditor;

################################################################################
# bootstrap
################################################################################

$root = dirname(__DIR__);
$time = time();

require "$root/tests/bootstrap.php";

################################################################################
# start
################################################################################

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saveyour Field Tests</title>

    <!-- select2 -->
    <link href="assets/lib/select2/css/select2.css" rel="stylesheet" />

    <!-- trix -->
    <link href="assets/lib/trix/trix.css" rel="stylesheet" type="text/css">
</head>
<body>
HTML;

echo "<h1>Visual Field Tests</h1><hr>";
echo Html::open('form', ['method' => 'post']);
echo new Submit();
echo '<hr>';

################################################################################
# Radio Selection
################################################################################
echo '<h2>RadioGroup Test</h2>';

$provider = new class() implements RadioGroupSelectionInterface
{
    public function provideSelectionsData(): array
    {
        return [
            [
                'value' => 'test-1',
                'label' => 'Test 1',
                'id' => 'radio-selection-test-1',
            ],
            [
                'value' => 'test-2',
                'label' => 'Test 2',
                'id' => 'radio-selection-test-2',
            ],
        ];
    }

    public function defineSelectionValue($item): string
    {
        return $item['value'];
    }

    public function defineSelectionId($item): string
    {
        return $item['id'];
    }

    public function defineSelectionLabel($item): string
    {
        return $item['label'];
    }
};

$selection = new RadioGroup();
$selection->setSelectionProvider($provider);
$selection->setLabelOptions([
    'classlist' => ['label-class', 'label-class-1'],
]);
$selection->setName('radio-group-test-1');
$selection->setValue('test-2');

echo '<h3>Inline</h3>';
echo $selection;

$selection->setIsInline(false);
$selection->setName('radio-group-test-2');
// $selection->setDisabled(true);

echo '<h3>Not Inline</h3>';
echo $selection;

echo '<hr>';

################################################################################
# Checklist
################################################################################
echo '<h2>Checklist Test</h2>';

$provider = new class() implements ChecklistItemsProviderInterface
{
    public function provideSelectionsData(): array
    {
        return [
            [
                'value' => 'test-1',
                'label' => 'Test 1',
                'id' => 'checklist-test-1',
            ],
            [
                'value' => 'test-2',
                'label' => 'Test 2',
                'id' => 'checklist-test-2',
            ],
        ];
    }

    public function defineSelectionValue($item): string
    {
        return $item['value'];
    }

    public function defineSelectionId($item): string
    {
        return $item['id'];
    }

    public function defineSelectionLabel($item): string
    {
        return $item['label'];
    }
};

$checklist = new Checklist();
$checklist->setSelectionProvider($provider);
$checklist->setValue('test-1');
$checklist->setName('checklist-test-1');
// $checklist->setDisabled(true);

echo $checklist->toHtml();
echo '<hr>';

################################################################################
# Select
################################################################################
echo '<h2>Select Test</h2>';

$select = new Select();

$provider1 = new class() implements OptionsProviderInterface
{
    public function provideSelectionsData(): array
    {
        return [
            [
                'value' => 'test-A',
                'text' => 'Test A',
            ],
            [
                'value' => 'test-B',
                'text' => 'Test B',
            ],
        ];
    }

    public function defineSelectionValue($item): string
    {
        return $item['value'];
    }

    public function defineSelectionText($item): string
    {
        return $item['text'];
    }
};

// $select->setRequired(true);
$select->setPlaceholder('Select Test');
$select->setSelectionProvider($provider1);
$select->setName('select-test-1');
// $select->setValue('test-B');

echo '<h3>Single Value</h3>';
echo $select->toHtml();

echo str_repeat('<br>', 2);

echo '<h3>Multi-Value with optgroups</h3>';

$provider2 = new class() implements OptionsProviderInterface
{
    public function provideSelectionsData(): array
    {
        return [
            [
                'value' => 'test-C',
                'text' => 'Test C',
            ],
            [
                'value' => 'test-D',
                'text' => 'Test D',
            ],
        ];
    }

    public function defineSelectionValue($item): string
    {
        return $item['value'];
    }

    public function defineSelectionText($item): string
    {
        return $item['text'];
    }
};

$provider3 = new class() implements OptionsProviderInterface
{
    public function provideSelectionsData(): array
    {
        return [
            [
                'value' => 'test-E',
                'text' => 'Test E',
            ],
            [
                'value' => 'test-F',
                'text' => 'Test F',
            ],
        ];
    }

    public function defineSelectionValue($item): string
    {
        return $item['value'];
    }

    public function defineSelectionText($item): string
    {
        return $item['text'];
    }
};

$select = new Select();

$optgroup1 = new OptGroup('Test Group 1');
$optgroup2 = new OptGroup('Test Group 2');
$optgroup3 = new OptGroup('Test Group 3');

$optgroup1->setSelectionProvider($provider1);
$optgroup2->setSelectionProvider($provider2);
$optgroup3->setSelectionProvider($provider3);

// $select->setRequired(true);
$select->setPlaceholder('Select Test 2');
$select->setGroups($optgroup1, $optgroup2, $optgroup3);
$select->setMultiple(true);
$select->setName('select-test-2');
// $select->setValue(['test-C', 'test-F']);

echo $select->toHtml();
echo '<hr>';

################################################################################
# Select2
################################################################################
echo Html::h2('Select2 Test');
$states = new StateSelectOptions();

#test1
$select2 = new Select2();
$select2->setName('select2-test-1');
$select2->setSelectionProvider($states);
$select2->setPlaceholder('Select2 Test 1 Placeholder');

echo $select2->toHtml();
echo str_repeat('<br>', 2);

#test2
$select2 = new Select2([
    'allowClear' => true,
]);
$select2->setName('select2-test-2');
$select2->setValue('MA');
$select2->setSelectionProvider($states);
$select2->setPlaceholder('Select2 Test 2 Placeholder');
$select2->setWidth('250px');
$select2->setTheme('classic');

echo $select2->toHtml();
echo str_repeat('<br>', 2);

#test3
$select2 = new Select2();
$select2->setName('select2-test-3');
$select2->setSelectionProvider($provider2);
$select2->setPlaceholder('Select2 Test 3 Placeholder');
$select2->setWidth('300px');
$select2->setMultiple(true);

echo $select2->toHtml();
echo str_repeat('<br>', 2);

#test4
$select2 = new Select2();
$select2->setName('select2-test-4');
$select2->setValue(['test-B', 'test-D']);
$select2->setPlaceholder('Select2 Test 4 Placeholder');
$select2->setWidth('300px');
$select2->setMultiple(true);
$select2->setSize(5);
$select2->setGroups($optgroup1, $optgroup2, $optgroup3);

echo $select2->toHtml();
echo str_repeat('<br>', 2);

echo '<hr>';

################################################################################
# Trix
################################################################################
echo Html::h2('Trix Editor Test');
$trix = new TrixEditor('test-control');
$trix->setId('test-trix');
$trix->setName('trix-test');
$trix->setValue('This is <del><b>not</b></del> a test <del>but it is now</del>.');

echo $trix;

################################################################################
# end
################################################################################
echo '<br><hr>';
echo new Submit();
echo Html::close('form');
echo "\n";

echo <<<HTML
    <!-- jquery -->
    <script src="assets/lib/jquery/jquery.js"></script>

    <!-- select2 -->
    <script src="assets/lib/select2/js/select2.js"></script>

    <!-- trix -->
    <script src="assets/lib/trix/trix.js" type="text/javascript"></script>

    <!-- saveyour -->
    <script src="assets/saveyour.js" ver="{$time}"></script>
</body>
</html>
HTML;
