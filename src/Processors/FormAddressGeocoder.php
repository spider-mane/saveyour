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
use Respect\Validation\Validator;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Controllers\FormDataProcessingCache;
use WebTheory\Saveyour\Formatters\LazyDataFormatter;
use WebTheory\Saveyour\InputPurifier;

class FormAddressGeocoder extends AbstractRestrictedFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $countryCode = 'US';

    /**
     * @var Provider
     */
    protected $provider;

    /**
     * @var FieldDataManagerInterface
     */
    protected $addressDataManager;

    /**
     * @var DataFormatterInterface
     */
    protected $geoDataFormatter;

    /**
     * @var FieldDataManagerInterface
     */
    protected $geoDataManager;

    /**
     * @var DataFormatterInterface
     */
    protected $addressDataFormatter;

    /**
     * {@inheritDoc}
     */
    public const ACCEPTED_FIELDS = ['street', 'city', 'state', 'zip', 'country'];

    public function __construct(
        Provider $provider,
        FieldDataManagerInterface $geoDataManager,
        ?DataFormatterInterface $geoDataFormatter = null,
        ?FieldDataManagerInterface $addressDataManager = null,
        ?DataFormatterInterface $addressDataFormatter = null
    ) {
        $this->provider = $provider;
        $this->geoDataManager = $geoDataManager;
        $this->geoDataFormatter = $geoDataFormatter ?? new LazyDataFormatter();

        if ($addressDataManager) {
            $this->addressDataManager = $addressDataManager;
            $this->addressDataFormatter = $addressDataFormatter ?? new LazyDataFormatter();
        }
    }

    /**
     * Get the value of countryCode
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Set the value of countryCode
     *
     * @param string $countryCode
     *
     * @return self
     */
    public function setCountryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    protected function formatAddress($fields)
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

    protected function createInputPurifier(): InputPurifier
    {
        return new InputPurifier(Validator::floatType());
    }

    public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface
    {
        if ($this->valueUpdated($results)) {
            return $this->processResults($request, $results);
        }

        return null;
    }

    protected function processResults(ServerRequestInterface $request, $results): FormDataProcessingCacheInterface
    {
        $address = $this->formatAddress($this->extractValues($results));
        $data = $this->provider
            ->geocodeQuery(GeocodeQuery::create($address))
            ->get(0)
            ->getCoordinates();

        $coordinates = $this->createInputPurifier()->filterInput([
            'lat' => $data->getLatitude(),
            'lng' => $data->getLongitude(),
        ]);

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

        return (new FormDataProcessingCache())
            ->withResult('coordinates', $coordinates)
            ->withResult('coordinates_updated', $geoUpdated)
            ->withResult('address_updated', $addressUpdated ?? false);
    }
}
