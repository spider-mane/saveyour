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
use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Controllers\ProcessedFieldReportBuilder;
use WebTheory\Saveyour\Processors\FormAddressGeocoder;

class FormAddressGeocoderTest extends TestCase
{
    protected function generateDummyDataManager()
    {
        return new class () implements FieldDataManagerInterface {
            public function getCurrentData(ServerRequestInterface $request)
            {
                return '';
            }

            public function handleSubmittedData(ServerRequestInterface $request, $data): bool
            {
                return true;
            }
        };
    }

    public function testGetsProperGeoData()
    {
        if (file_exists($env = dirname(__DIR__) . '/env.php')) {
            require $env;
        }

        $address = [
            'street' => 'One Microsoft Way',
            'city' => 'Redmond',
            'state' => 'WA',
            'zip' => '98052',
        ];

        $request = ServerRequest::fromGlobals();

        $results = [
            'street' => (new ProcessedFieldReportBuilder())
                ->withSanitizedInputValue($address['street'])
                ->withUpdateSuccessful(true),

            'city' => (new ProcessedFieldReportBuilder())
                ->withSanitizedInputValue($address['city'])
                ->withUpdateSuccessful(true),

            'state' => (new ProcessedFieldReportBuilder())
                ->withSanitizedInputValue($address['state'])
                ->withUpdateSuccessful(true),

            'zip' => (new ProcessedFieldReportBuilder())
                ->withSanitizedInputValue($address['zip'])
                ->withUpdateSuccessful(true),
        ];

        $address = (new Address())
            ->withAddressLine1($address['street'])
            ->withLocality($address['city'])
            ->withAdministrativeArea($address['state'])
            ->withPostalCode($address['zip'])
            ->withCountryCode('US');

        $formatter = new DefaultFormatter(
            new AddressFormatRepository(),
            new CountryRepository(),
            new SubdivisionRepository(),
            ['html' => false]
        );

        $client = new Client();
        $geocoder = new GoogleMaps($client, null, getenv('GOOGLE_MAPS'));

        $collection = $geocoder->geocodeQuery(GeocodeQuery::create($formatter->format($address)));
        $coordinates = $collection->get(0)->getCoordinates();

        $coordinates = [
            'lat' => $coordinates->getLatitude(),
            'lng' => $coordinates->getLongitude(),
        ];

        $manager = $this->generateDummyDataManager();

        $processor = (new FormAddressGeocoder($geocoder, $manager))
            ->addField('street', 'street')
            ->addField('city', 'city')
            ->addField('state', 'state')
            ->addField('zip', 'zip');

        $results = $processor->process($request, $results)->results('coordinates');

        $this->assertEquals($coordinates, $results);
    }
}
