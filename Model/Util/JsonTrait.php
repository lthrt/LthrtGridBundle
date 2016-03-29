<?php

namespace Lthrt\GridBundle\Model\Util;

trait JsonTrait
{
    public function json($full = false)
    {
        return json_encode($this->jsonFields($full));
    }
}
