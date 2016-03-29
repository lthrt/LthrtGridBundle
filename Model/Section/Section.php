<?php

namespace Lthrt\GridBundle\Model\Section;

class Section
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_row = [];

    public function __construct($opt = null, $attr = null)
    {
        // For building Grid
        $this->opt = $opt;

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addRow(Row $row)
    {
        $this->_row[] = $row;
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
                    'row' => array_map(
                        function ($r) use ($full) {
                            return $r->jsonFields($full);
                        }, $this->_row),
                ]
            );
        }

        return $fields;
    }

}
