<?php

namespace Lthrt\GridBundle\Model;

class Maker
{
    use GetSetTrait;

    private $em;
    private $dumper;
    // Grid
    private $g;
    // Query
    private $q;
    private $router;

    public function __construct($em, $router, $dumper)
    {
        $this->em     = $em;
        $this->router = $router;
        $this->dumper = $dumper;
    }

    public function mapQueryBuilder($qb)
    {
        // Map these and store final query in local field
        //
        $mapper  = new Mapper($this->em);
        $this->q = $mapper->mapQueryBuilder($qb);
        return $this;
    }

    public function init($opt = [], $attr = [])
    {
        $this->g = new Grid($opt, $attr);
        $this->g->addSection(new Head($opt, $attr));
        $this->g->addSection(new Body($opt, $attr));
    }

}
