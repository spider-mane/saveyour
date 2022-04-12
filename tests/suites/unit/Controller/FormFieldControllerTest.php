<?php

namespace Tests\Suites\Unit\Controller;

use Faker\UniqueGenerator;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;
use WebTheory\Saveyour\Controller\FormFieldController;

class FormFieldControllerTest extends TestCase
{
    protected FormFieldController $sut;

    protected FormFieldInterface $mockField;

    protected FieldDataManagerInterface $mockDataManager;

    protected ValidatorInterface $mockValidator;

    protected DataFormatterInterface $mockFormatter;

    protected ServerRequestInterface $mockRequest;

    protected ValidationReportInterface $mockValidationReport;

    protected string $dummyRequestVar;

    protected string $dummyInputValue;

    protected array $dummyRequestBody;

    protected function setUp(): void
    {
        parent::setUp();

        $requestVar = $this->fake->slug;
        $inputValue = $this->fake->sentence;
        $requestBody = [$requestVar => $inputValue];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn($requestBody);

        $validationReport = $this->createMock(ValidationReportInterface::class);
        $validationReport->method('validationStatus')->willReturn(true);
        $validationReport->method('ruleViolations')->willReturn([]);

        $field = $this->createMock(FormFieldInterface::class);

        $dataManager = $this->createMock(FieldDataManagerInterface::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('inspect')->willReturn($validationReport);
        $validator->method('validate')->willReturn(true);

        $formatter = $this->createMock(DataFormatterInterface::class);
        $formatter->method('formatInput')->willReturn($inputValue);

        $this->dummyRequestVar = $requestVar;
        $this->dummyInputValue = $inputValue;
        $this->dummyRequestBody = $requestBody;

        $this->mockRequest = $request;
        $this->mockValidationReport = $validationReport;
        $this->mockField = $field;
        $this->mockDataManager = $dataManager;
        $this->mockValidator = $validator;
        $this->mockFormatter = $formatter;

        $this->sut = new FormFieldController(
            $requestVar,
            $this->mockField,
            $this->mockDataManager,
            $this->mockValidator,
            $this->mockFormatter
        );
    }

    /**
     * @test
     */
    public function it_is_instance_of_FormFieldControllerInterface()
    {
        $this->assertInstanceOf(FormFieldControllerInterface::class, $this->sut);
    }

    /**
     * @test
     */
    public function it_returns_provided_request_var()
    {
        # Arrange
        $requestVar = $this->fake->slug;
        $sut = new FormFieldController($requestVar);

        # Act
        $result = $sut->getRequestVar();

        # Assert
        $this->assertSame($requestVar, $result);
    }

    /**
     * @test
     */
    public function it_returns_specified_fields_to_await()
    {
        # Arrange
        $await = $this->dummyList(fn (UniqueGenerator $unique) => $unique->slug);
        $sut = new FormFieldController($this->fake->slug, null, null, null, null, null, $await);

        # Act
        $result = $sut->mustAwait();

        # Assert
        $this->assertSame($await, $result);
    }

    /**
     * @test
     * @dataProvider formFieldDataProvider
     */
    public function it_returns_provided_form_field(?FormFieldInterface $field)
    {
        # Arrange
        $sut = new FormFieldController($this->fake->slug, $field);

        # Act
        $result = $sut->getFormField();

        # Assert
        $this->assertSame($field, $result);
    }

    public function formFieldDataProvider(): array
    {
        return [
            'with form field' => [$this->createMock(FormFieldInterface::class)],
            'no form field' => [null],
        ];
    }

    /**
     * @test
     */
    public function it_throws_a_logic_exception_if_compose_is_called_in_absence_of_form_field()
    {
        # Arrange
        $sut = new FormFieldController($this->fake->slug);

        # Expect
        $this->expectException(LogicException::class);

        # Act
        $sut->compose($this->mockRequest);
    }

    /**
     * @test
     */
    public function it_throws_a_logic_exception_if_render_is_called_in_absence_of_form_field()
    {
        # Arrange
        $sut = new FormFieldController($this->fake->slug);

        # Expect
        $this->expectException(LogicException::class);

        # Act
        $sut->render($this->mockRequest);
    }

    /**
     * @test
     */
    public function it_passes_input_value_and_request_to_data_manager()
    {
        # Expect
        $this->mockDataManager->expects($this->once())
            ->method('handleSubmittedData')
            ->with($this->mockRequest, $this->dummyInputValue);

        # Act
        $this->sut->process($this->mockRequest);
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_accurately_validates_input_value(bool $isValid)
    {
        # Arrange
        $validator = $this->createMock(ValidatorInterface::class);

        $sut = new FormFieldController($this->fake->slug, null, null, $validator);

        # Expect
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->dummyInputValue)
            ->willReturn($isValid);

        # Act
        $result = $sut->validate($this->dummyInputValue);

        # Assert
        $this->assertEquals($isValid, $result);
    }

    public function validationDataProvider(): array
    {
        return [
            'valid' => [true],
            'invalid' => [false],
        ];
    }

    /**
     * @test
     */
    public function it_populates_form_field_with_value_provided_by_data_manager()
    {
        # Arrange
        $value = $this->fake->sentence;
        $this->mockFormatter->method('formatData')->willReturn($value);
        $this->mockDataManager->method('getCurrentData')->willReturn($value);

        # Expect
        $this->mockField->expects($this->once())
            ->method('setValue')
            ->with($value);

        # Act
        $this->sut->compose($this->mockRequest)->getValue();
    }

    /**
     * @test
     * @dataProvider requestCheckDataProvider
     */
    public function it_can_access_whether_or_not_specified_request_variable_is_present_in_request_object(
        bool $isPresent,
        string $requestVar,
        array $requestBody
    ) {
        # Arrange
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn($requestBody);

        $sut = new FormFieldController($requestVar);

        # Act
        $result = $sut->requestVarPresent($request);

        # Assert
        $this->assertSame($isPresent, $result);
    }

    public function requestCheckDataProvider(): array
    {
        $fake = $this->createFaker();
        $unique = $fake->unique();

        $present = $fake->slug;

        return [
            'present' => [true, $present, [$present => $fake->sentence]],
            'not present' => [false, $unique->slug, [$unique->slug => $fake->sentence]],
        ];
    }

    /**
     * @test
     * @dataProvider processPermissionDataProvider
     */
    public function it_is_honest_about_whether_or_not_it_is_allowed_to_process_incoming_data(bool $isAllowed)
    {
        # Arrange
        $sut = new FormFieldController($this->fake->slug, null, null, null, null, $isAllowed);

        # Act
        $query = $sut->isPermittedToProcess();

        # Assert
        $this->assertSame($isAllowed, $query);
    }

    public function processPermissionDataProvider(): array
    {
        return [
            'allowed' => [true],
            'not allowed' => [false],
        ];
    }

    /**
     * @test
     */
    public function it_provides_empty_string_if_no_data_manager_is_provided()
    {
        # Arrange
        $sut = new FormFieldController($this->fake->slug, $this->mockField);

        # Expect
        $this->mockField->expects($this->once())
            ->method('setValue')
            ->with('');

        # Act
        $sut->compose($this->mockRequest);
        $result = $sut->getPresetValue($this->mockRequest);

        # Assert
        $this->assertSame('', $result);
    }
}
