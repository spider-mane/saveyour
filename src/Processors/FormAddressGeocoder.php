<?php

namespace WebTheory\Saveyour\Processors;

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\InputFormatterInterface;
use WebTheory\Saveyour\Controllers\Report\FormProcessReportBuilder;
use WebTheory\Saveyour\Formatters\LazyDataFormatter;

class FormAddressGeocoder extends AbstractRestrictedFormDataProcessor implements FormDataProcessorInterface
{
    protected string $countryCode = 'US';

    protected Provider $provider;

    protected FieldDataManagerInterface $addressDataManager;

    protected InputFormatterInterface $geoDataFormatter;

    protected FieldDataManagerInterface $geoDataManager;

    protected InputFormatterInterface $addressDataFormatter;

    /**
     * {@inheritDoc}
     */
    public const ACCEPTED_FIELDS = ['street', 'city', 'state', 'zip', 'country'];

    public function __construct(
        string $name,
        array $fields,
        Provider $provider,
        FieldDataManagerInterface $geoDataManager,
        ?InputFormatterInterface $geoDataFormatter = null,
        ?FieldDataManagerInterface $addressDataManager = null,
        ?InputFormatterInterface $addressDataFormatter = null,
        string $countryCode = 'US'
    ) {
        parent::__construct($name, $fields);

        $this->provider = $provider;
        $this->geoDataManager = $geoDataManager;
        $this->geoDataFormatter = $geoDataFormatter ?? new LazyDataFormatter();

        if ($addressDataManager) {
            $this->addressDataManager = $addressDataManager;
            $this->addressDataFormatter = $addressDataFormatter ?? new LazyDataFormatter();
        }

        $this->countryCode = $countryCode;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        return $this->valueUpdated($results)
            ? $this->processResults($request, $results)
            : null;
    }

    protected function processResults(ServerRequestInterface $request, $results): FormProcessReportInterface
    {
        $address = $this->getFormattedAddress($this->extractValues($results));

        $query = $this->provider->geocodeQuery(GeocodeQuery::create($address));
        $data = $query->first()->getCoordinates();

        $coordinates = [
            'lat' => $data->getLatitude(),
            'lng' => $data->getLongitude(),
        ];

        $geoUpdated = $this->geoDataManager->handleSubmittedData(
            $request,
            $this->formatGeoDataInput($coordinates)
        );

        if (isset($this->addressDataManager)) {
            $addressUpdated = $this->addressDataManager->handleSubmittedData(
                $request,
                $this->formatAddressInput($address)
            );
        }

        $reportBuilder = new FormProcessReportBuilder();

        return $reportBuilder
            ->withProcessResult('coordinates', $coordinates)
            ->withProcessResult('coordinates_updated', $geoUpdated)
            ->withProcessResult('address_updated', $addressUpdated ?? false)
            ->build();
    }

    protected function getFormattedAddress(array $fields): string
    {
        $address = (new Address())
            ->withAddressLine1($fields['street'])
            ->withLocality($fields['city'])
            ->withAdministrativeArea($fields['state'])
            ->withPostalCode($fields['zip'])
            ->withCountryCode($fields['country'] ?? $this->countryCode);

        $options = [
            'html' => false,
        ];

        $formatter = new DefaultFormatter(
            new AddressFormatRepository(),
            new CountryRepository(),
            new SubdivisionRepository(),
            $options
        );

        return str_replace("\n", ", ", $formatter->format($address));
    }

    protected function formatGeoDataInput($value)
    {
        return $this->geoDataFormatter->formatInput($value);
    }

    protected function formatAddressInput($value)
    {
        return $this->addressDataFormatter->formatInput($value);
    }
}
