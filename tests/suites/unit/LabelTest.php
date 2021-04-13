<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Elements\Label;

class LabelTest extends TestCase
{
    public function testEvenIsLabel()
    {
        $html = '<label id="foo" for="bar">Foobar</label>';

        $label = new Label('Foobar');
        $label->setId('foo');
        $label->setFor('bar');

        $this->assertEquals($html, $label->toHtml());
        $this->assertEquals('Foobar', $label->getContent());
    }
}
