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
use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Processors\FormAddressGeocoder;

class FormAddressGeocoderTest extends TestCase
{
    /**
     *
     */
    protected function generateDummyDataManager() {
        return new class implements FieldDataManagerInterface {
            public function getCurrentData(\Psr\Http\Message\ServerRequestInterface $request)
            {
                return '';
            }

            public function handleSubmittedData(\Psr\Http\Message\ServerRequestInterface $request, $data): bool
            {
                return true;
            }
        }
    }

    /**
     *
     */
    public function testGetsProperGeoData()
    {
        $values = include '../values.php';
        $address = $values['dummy_address'];

        $request = ServerRequest::fromGlobals()
            ->withMethod('POST')
            ->withParsedBody([
                'street' => $address['street'],
                'city' => $address['city'],
                'state' => $address['state'],
                'zip' => $address['zip'],
            ]);

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

        $manager = $this->generateDummyDataManager();

        $processor = (new FormAddressGeocoder($manager, $geocoder))
        ->addField('street', 'street')
        ->addField('city', 'city')
        ->addField('state', 'state')
        ->addField('zip', 'zip');

        $results = $processor->process($re)
    }
}
