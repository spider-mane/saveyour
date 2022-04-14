<?php

namespace Tests\Suites\Unit\Field\Abstracts;

use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Field\Abstracts\LabelMakerTrait;
use WebTheory\Saveyour\Field\Element\Label;

class LabelMakerTraitTest extends UnitTestCase
{
    public function generateDummyClass()
    {
        return new class () {
            use LabelMakerTrait;

            public function create($content, $options)
            {
                return $this->createLabel($content, $options);
            }
        };
    }

    /**
     * @test
     */
    public function it_creates_an_appropriately_configured_Label_instance()
    {
        # Arrange
        $content = $this->fake->word;
        $options = [
            'id' => $this->fake->slug,
            'for' => $this->fake->slug,
        ];

        $sut = $this->generateDummyClass();

        # Act
        $label = $sut->create($content, $options);

        # Assert
        $this->assertInstanceOf(Label::class, $label);
        $this->assertEquals($content, $label->getContent());
        $this->assertEquals($options['id'], $label->getId());
        $this->assertEquals($options['for'], $label->getFor());
    }
}
