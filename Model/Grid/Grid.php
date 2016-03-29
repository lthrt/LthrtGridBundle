<?php

namespace Lthrt\GridBundle\Model\Grid;

use Lthrt\GridBundle\Model\Column\Column;
use Lthrt\GridBundle\Model\Section\Section;

class Grid
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_column  = [];
    private $_section = [];

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
        $this->_section[] = $section;
    }

    public function addColumn($alias, Column $column)
    {
        $this->_column[$alias] = $column;
    }

    public function getColumn()
    {
        return $this->_column;
    }

    public function jsonFields($full = false)
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) use ($full) {
                return $full || "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );

        if (!$full) {
            $fields = array_merge($fields,
                [
                    'section' => array_map(
                        function ($s) use ($full) {
                            return $s->jsonFields($full);
                        }, $this->_section),
                ]
            );
        }
        return $fields;
    }
}
