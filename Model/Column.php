<?php

namespace Lthrt\GridBundle\Model;

class Column
{
    use AttributesTrait;
    use GetSetTrait;
    use JsonTrait;
    use OptionsTrait;

    private $grid;

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt = $opt;

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

}
