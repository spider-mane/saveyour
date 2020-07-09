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
