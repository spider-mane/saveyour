<?php

namespace Tests\Suites\Unit\Field;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Concerns\LabelMaker;
use WebTheory\Saveyour\Element\Label;

class LabelMakerTest extends TestCase
{
    public function generateDummyClass()
    {
        return new class()
        {
            use LabelMaker;

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
