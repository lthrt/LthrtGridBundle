<?php

namespace Lthrt\GridBundle\Model;

trait JsonTrait
{
    public function jsonSerialize()
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) {
                return "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );
        return $fields;
    }

    public function json($full = true)
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) use ($full) {
                return $full || "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );
        return json_encode($fields);
    }
}
