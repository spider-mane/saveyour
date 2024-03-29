<?php

namespace Tests\Suites\Unit\Field\Element;

use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Field\Element\Label;

class LabelTest extends UnitTestCase
{
    /**
     * @test
     */
    public function it_outputs_a_valid_label_element()
    {
        $id = $this->unique->slug;
        $for = $this->unique->slug;
        $text = $this->fake->word;

        $html = "<label id=\"$id\" for=\"$for\">$text</label>";

        $label = new Label($text);
        $label->setId($id);
        $label->setFor($for);

        $this->assertEquals($html, $label->toHtml());
        $this->assertEquals($text, $label->getContent());
    }
}
