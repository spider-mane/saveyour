<?php

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Query\GeocodeQuery;
use GuzzleHttp\Psr7\ServerRequest;
use Http\Adapter\Guzzle6\Client;
use WebTheory\Saveyour\Contracts\ChecklistItemsInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Controllers\FormFieldControllerBuilder;
use WebTheory\Saveyour\Fields\Checklist;
use WebTheory\Saveyour\Fields\RadioSelection;
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

echo "this is a test";

$list = new RadioSelection([
    'test-1' => 'Test 1',
    'test-2' => 'Test 2',
]);

$list
    ->setLabelOptions([
        'classlist' => ['label-class', 'label-class-1']
    ])
    ->setName('test-name')
    ->setValue('test-2');

dump($list->toHtml());

echo $list;

$request = ServerRequest::fromGlobals()
    ->withAttribute('post_id', 45)
    ->withParsedBody([
        'test' => '5'
    ]);

$address = [
    'street' => 'One Microsoft Way',
    'city' => 'Redmond',
    'state' => 'WA',
    'zip' => '98052',
];

$address = (new Address)
    ->withAddressLine1($address['street'])
    ->withLocality($address['city'])
    ->withAdministrativeArea($address['state'])
    ->withPostalCode($address['zip'])
    ->withCountryCode('US');

$options = [
    'html' => false,
];

$display = new DefaultFormatter(
    new AddressFormatRepository,
    new CountryRepository,
    new SubdivisionRepository,
    $options
);

$formatted = str_replace("\n", ', ', $display->format($address));

$client = new Client();
$geocoder = new GoogleMaps($client, null, getenv('GOOGLE_MAPS'));

$collection = $geocoder->geocodeQuery(GeocodeQuery::create($display->format($address)));
$coordinates = $collection->get(0)->getCoordinates();

$coordinates = [
    'lat' => $coordinates->getLatitude(),
    'lng' => $coordinates->getLongitude(),
];

var_dump($coordinates);

$selection = [
    'test-1' => [
        'label' => 'Test 1',
        'id' => 'test-1'
    ],
    'test-2' => [
        'label' => 'Test 2',
        'id' => 'test-2'
    ]
];

$provider = new class implements ChecklistItemsInterface
{
    public function provideItemsAsRawData(): array
    {
        return [
            [
                'value' => 'test-3',
                'label' => 'Test 3',
                'id' => 'test-3'
            ],
            [
                'value' => 'test-4',
                'label' => 'Test 4',
                'id' => 'test-4'
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
$checklist->setItems($selection);

echo $checklist->toHtml();

$checklist->setChecklistItemProvider($provider);
echo $checklist->toHtml();
echo '<hr>';


################################################################################
#
################################################################################

// $field1 = new FormFieldControllerBuilder('test-1');
// $field2 = new FormFieldControllerBuilder('test-2');
// $field3 = new FormFieldControllerBuilder('test-3');
// $field4 = new FormFieldControllerBuilder('test-4');
// $field5 = new FormFieldControllerBuilder('test-5');

// $field1->await('test-3');
// $field3->await('test-4');

// $field1 = $field1->create();
// $field2 = $field2->create();
// $field3 = $field3->create();
// $field4 = $field4->create();
// $field5 = $field5->create();

// // $fields = [$field3, $field1, $field2];
// $fields = [$field1, $field2, $field3, $field4, $field5];
// // $fields = [$field1, $field3];
// var_dump($fields);

// usort($fields, function (FormFieldControllerInterface $a, FormFieldControllerInterface $b) {

//     var_dump($a->getRequestVar(), $b->getRequestVar());
//     echo '<hr>';

//     // because usort will not compare values that it infers from previous
//     // comparisons are equal, 0 should never be returned. all that matters is
//     // that dependent fields are positioned after their dependencies.
//     return in_array($a->getRequestVar(), $b->mustAwait()) ? -1 : 1;
// });

// var_dump($fields);

################################################################################
#
################################################################################
