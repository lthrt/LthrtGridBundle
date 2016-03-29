<?php

namespace Lthrt\GridBundle\Model;

class Grid
{
    use AttributesTrait;
    use GetSetTrait;
    use JsonTrait;
    use OptionsTrait;

    private $columns = [];
    private $section = [];

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt        = $opt;
        $this->opt['tag'] = 'TABLE';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addSection(Section $section)
    {
        $this->section[] = $section;
    }

    public function addColumn(Column $column)
    {
        $this->column[] = $column;
    }
}
