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

    const HEAD = 'head';
    const BODY = 'body';

    private $_column  = [];
    private $_section = [];

    public function __construct($opt = [], $attr = [])
    {
        // For building Grid
        $this->opt        = $opt;
        $this->opt['tag'] = 'table';

        //For rendering HTMl attributes
        $this->attr = $attr;
    }

    public function addSection($alias, Section $section)
    {
        $this->_section[$alias] = $section;

        return $this;
    }

    public function getBody()
    {
        return $this->_section[Grid::BODY];
    }

    public function getHead()
    {
        return $this->_section[Grid::HEAD];
    }

    public function addColumn($alias, Column $column)
    {
        $this->_column[$alias] = $column;

        return $this;
    }

    public function clearColumns()
    {
        return $this->_column = [];
    }

    public function getColumn()
    {
        return $this->_column;
    }

    public function reAliasColumn($old, $new)
    {
        $this->_column[$new] = $this->_column[$old];
        unset($this->_column[$old]);

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
        } else {
            $fields = array_merge($fields,
                [
                    '_section' => array_map(
                        function ($s) use ($full) {
                            return $s->jsonFields($full);
                        }, $this->_section),
                ]
            );
        }

        return ['table' => $fields];
    }

    public function html()
    {
        $sec = implode("\n", array_map(function ($s) {return $s->html();}, $this->_section));
        $attr = "";
        if ($this->attr) {
            foreach ($this->attr as $key => $value) {
                $attr .= " " . $key . "=\"" . $value . "\"";
            }
        }
        $table = "<" . $this->getOpt('tag') . $attr . ">\n" . $sec . "\n</" . $this->getOpt('tag') . ">";

        return $table;
    }
}
