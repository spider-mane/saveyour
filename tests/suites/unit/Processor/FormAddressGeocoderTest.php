<?php

namespace Tests\Suites\Unit\Processor;

use Geocoder\Collection;
use Geocoder\Location;
use Geocoder\Model\Coordinates;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Formatting\InputFormatterInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;
use WebTheory\Saveyour\Processor\FormAddressGeocoder;

class FormAddressGeocoderTest extends UnitTestCase
{
    protected FormAddressGeocoder $sut;

    protected ServerRequestInterface $mockRequest;

    protected Provider $mockProvider;

    protected FieldDataManagerInterface $mockGeoDataManager;

    protected InputFormatterInterface $mockGeoDataFormatter;

    protected FieldDataManagerInterface $mockAddressDataManager;

    protected InputFormatterInterface $mockAddressDataFormatter;

    protected Coordinates $coordinatesInstance;

    protected Location $mockLocation;

    protected Collection $mockCollection;

    protected string $dummyCountryCode;

    protected array $dummyFieldResults;

    protected float $dummyLatitude;

    protected float $dummyLongitude;

    protected array $dummyCoordinates;

    protected array $dummyFields;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyCountryCode = $this->fake->countryCode;

        $this->dummyLatitude = $this->fake->latitude;
        $this->dummyLongitude = $this->fake->longitude;
        $this->dummyCoordinates = [
            'lat' => $this->dummyLatitude,
            'lng' => $this->dummyLongitude,
        ];

        $this->mockRequest = $this->createMock(ServerRequestInterface::class);

        $this->coordinatesInstance = new Coordinates($this->dummyLatitude, $this->dummyLongitude);

        $this->mockLocation = $this->createMock(Location::class);
        $this->mockLocation->method('getCoordinates')->willReturn($this->coordinatesInstance);

        $this->mockCollection = $this->createMock(Collection::class);
        $this->mockCollection->method('first')->willReturn($this->mockLocation);

        $this->mockProvider = $this->createMock(Provider::class);
        $this->mockProvider->method('geocodeQuery')->willReturn($this->mockCollection);

        $this->mockGeoDataManager = $this->createMock(FieldDataManagerInterface::class);
        $this->mockGeoDataFormatter = $this->createMock(InputFormatterInterface::class);

        $this->mockAddressDataManager = $this->createMock(FieldDataManagerInterface::class);
        $this->mockAddressDataFormatter = $this->createMock(InputFormatterInterface::class);

        $fields = [
            'street' => $this->unique->slug,
            'city' => $this->unique->slug,
            'state' => $this->unique->slug,
            'zip' => $this->unique->slug,
            'country' => $this->unique->slug,
        ];

        $results = [
            $fields['street'] => $this->fake->streetAddress,
            $fields['city'] => $this->fake->city,
            $fields['state'] => $this->fake->state,
            $fields['zip'] => $this->fake->postcode,
            $fields['country'] => $this->dummyCountryCode,
        ];

        foreach ($results as $param => $value) {
            $report = $this->createMock(ProcessedInputReportInterface::class);
            $report->method('rawInputValue')->willReturn($value);
            $report->method('updateSuccessful')->willReturn(true);

            $this->dummyFieldResults[$param] = $report;
        }

        $this->dummyFields = $fields;

        $this->sut = new FormAddressGeocoder(
            $this->fake->slug,
            $this->dummyFields,
            $this->mockProvider,
            $this->mockGeoDataManager,
            $this->mockGeoDataFormatter,
            $this->mockAddressDataManager,
            $this->mockAddressDataFormatter,
            $this->dummyCountryCode
        );
    }

    /**
     * @test
     */
    public function it_retrieves_geo_data_using_geo_library_api()
    {
        # Arrange
        $coordinates = $this->makeMockeryOf($this->coordinatesInstance);

        $location = $this->createMock(Location::class);
        $location->method('getCoordinates')->willReturn($coordinates);

        $collection = $this->createMock(Collection::class);
        $collection->method('first')->willReturn($location);

        $provider = $this->createMock(Provider::class);

        $sut = new FormAddressGeocoder(
            $this->fake->slug,
            $this->dummyFields,
            $provider,
            $this->mockGeoDataManager,
        );

        # Expect
        $provider->expects($this->once())
            ->method('geocodeQuery')
            ->with($this->isInstanceOf(GeocodeQuery::class))
            ->willReturn($collection);

        $coordinates->shouldReceive('getLatitude')->once()->andReturn($this->dummyLatitude);
        $coordinates->shouldReceive('getLongitude')->once()->andReturn($this->dummyLongitude);

        # Act
        $sut->process($this->mockRequest, $this->dummyFieldResults);
    }

    /**
     * @test
     */
    public function it_adds_coordinates_entry_to_report()
    {
        # Act
        $results = $this->sut->process($this->mockRequest, $this->dummyFieldResults);

        # Assert
        $this->assertEquals($this->dummyCoordinates, $results->resultFor('coordinates'));
    }

    /**
     * @test
     */
    public function it_passes_coordinates_to_data_manager_after_formatting()
    {
        # Expect
        $this->mockGeoDataFormatter->expects($this->once())
            ->method('formatInput')
            ->with($this->dummyCoordinates)
            ->willReturn($this->dummyCoordinates);

        $this->mockGeoDataManager->expects($this->once())
            ->method('handleSubmittedData')
            ->with($this->mockRequest, $this->dummyCoordinates);

        # Act
        $this->sut->process($this->mockRequest, $this->dummyFieldResults);
    }
}
