<?php

namespace Lthrt\GridBundle\Model\Section;

use Lthrt\GridBundle\Model\Row\Row;

class Section
{
    use \Lthrt\GridBundle\Model\Util\AttributesTrait;
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;
    use \Lthrt\GridBundle\Model\Util\JsonTrait;
    use \Lthrt\GridBundle\Model\Util\OptionsTrait;

    private $_row = [];

    public function __construct($opt = [], $attr = [])
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
        } else {
            $fields = array_merge($fields,
                [
                    '_row' => array_map(
                        function ($r) use ($full) {
                            return $r->jsonFields($full);
                        }, $this->_row),
                ]
            );
        }

        return $fields;
    }

    public function html()
    {
        $tr = implode("\n", array_map(function ($t) {return $t->html();}, $this->_row));
        $attr = "";
        if ($this->attr) {
            foreach ($this->attr as $key => $value) {
                $attr .= " " . $key . "=\"" . $value . "\"";
            }
        }
        $sec = "<" . $this->getOpt('tag') . $attr . ">\n" . $tr . "\n</" . $this->getOpt('tag') . ">";
        return $sec;
    }
}
