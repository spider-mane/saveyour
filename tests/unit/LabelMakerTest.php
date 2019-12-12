<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Concerns\LabelMaker;
use WebTheory\Saveyour\Elements\Label;

class LabelMakerTest extends TestCase
{
    public function generateDummyClass()
    {
        return new class
        {
            use LabelMaker;

            public function create($content, $options)
            {
                return $this->createLabel($content, $options);
            }
        };
    }

    public function testCreatesLabelWithOptions()
    {
        $maker = $this->generateDummyClass();

        $content = 'foobar';
        $options = [
            'id' => 'foo',
            'for' => 'bar',
            'attributes' => [
                'data-test' => 'foobar'
            ]
        ];

        $label = $maker->create($content, $options);

        $this->assertInstanceOf(Label::class, $label);

        $this->assertEquals($content, $label->getContent());
        $this->assertEquals($options['id'], $label->getId());
        $this->assertEquals($options['for'], $label->getFor());
        $this->assertEquals($options['attributes'], $label->getAttributes());
    }
}
