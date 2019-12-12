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
use WebTheory\Saveyour\Contracts\DataTransformerInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Controllers\FormDataProcessingCache;
use WebTheory\Saveyour\InputPurifier;
use WebTheory\Saveyour\Transformers\LazyTransformer;

class FormAddressGeocoder extends AbstractRestrictedFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var array
     */
    protected $fields = [
        'street' => null,
        'city' => null,
        'state' => null,
        'zip' => null,
        'country' => null,
    ];

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
     * @var DataTransformerInterface
     */
    protected $geoDataTransformer;

    /**
     * @var FieldDataManagerInterface
     */
    protected $geoDataManager;

    /**
     * @var DataTransformerInterface
     */
    protected $addressDataTransformer;

    /**
     * {@inheritDoc}
     */
    public const ACCEPTED_FIELDS = ['street', 'city', 'state', 'zip', 'country'];

    /**
     *
     */
    public function __construct(
        Provider $provider,
        FieldDataManagerInterface $geoDataManager,
        ?DataTransformerInterface $geoDataTransformer = null,
        ?FieldDataManagerInterface $addressDataManager = null,
        ?DataTransformerInterface $addressDataTransformer = null
    ) {
        $this->provider = $provider;
        $this->geoDataManager = $geoDataManager;
        $this->geoDataTransformer = $geoDataTransformer ?? new LazyTransformer();

        if ($addressDataManager) {
            $this->addressDataManager = $addressDataManager;
            $this->addressDataTransformer = $addressDataTransformer ?? new LazyTransformer();
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

    /**
     *
     */
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

    /**
     *
     */
    protected function transformGeoData($value)
    {
        return $this->geoDataTransformer->reverseTransform($value);
    }

    /**
     *
     */
    protected function transformAddress($value)
    {
        return $this->addressDataTransformer->reverseTransform($value);
    }

    /**
     *
     */
    protected function createInputPurifier(): InputPurifier
    {
        return (new InputPurifier())
            ->addRule('float', Validator::floatType())
            ->addFilter(function ($input) {
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
            });
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface
    {
        if ($this->valueUpdated($results)) {
            return $this->processResults($request, $results);
        }

        return null;
    }

    /**
     *
     */
    protected function processResults(ServerRequestInterface $request, $results): FormDataProcessingCacheInterface
    {
        $address = $this->formatAddress($this->extractValues($results));
        $data = $this->provider
            ->geocodeQuery(GeocodeQuery::create($address))
            ->get(0)
            ->getCoordinates();

        $coordinates = $this->createInputPurifier()->filterInput([
            'lat' => $data->getLatitude(),
            'lng' => $data->getLongitude()
        ]);

        $geoUpdated = $this->geoDataManager->handleSubmittedData(
            $request,
            $this->transformGeoData($coordinates)
        );

        if (isset($this->addressDataManager)) {
            $addressUpdated = $this->addressDataManager->handleSubmittedData(
                $request,
                $this->transformAddress($address)
            );
        }

        return (new FormDataProcessingCache)
            ->withResult('coordinates', $coordinates)
            ->withResult('coordinates_updated', $geoUpdated)
            ->withResult('address_updated', $addressUpdated);
    }
}
