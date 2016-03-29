<?php

namespace Lthrt\GridBundle\Model;

class Row
{
    use AttributesTrait;
    use GetSetTrait;
    use JsonTrait;
    use OptionsTrait;

    private $cell = [];
    private $section;

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt = $opt;
        // for Header-type Rows reset this
        $this->opt['tag'] = 'TR';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addCell(Cell $cell)
    {
        $this->cell[] = $cell;
    }
}
