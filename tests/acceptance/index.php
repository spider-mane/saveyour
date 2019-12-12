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

$values = include '../values.php';

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

$address = $values['dummy_address'];
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
$geocoder = new GoogleMaps($client, null, $values['google_maps']);

$collection = $geocoder->geocodeQuery(GeocodeQuery::create($display->format($address)));
$coordinates = $collection->get(0)->getCoordinates();

$coordinates = [
    'lat' => $coordinates->getLatitude(),
    'lng' => $coordinates->getLongitude(),
];

var_dump($coordinates);
