<?php

namespace Lthrt\GridBundle\Model;

class Section implements \JsonSerializable
{
    use AttributesTrait;
    use GetSetTrait;
    use JsonTrait;
    use OptionsTrait;

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt = $opt;

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addRow(Row $row)
    {
        $this->row[] = $row;
    }
}
