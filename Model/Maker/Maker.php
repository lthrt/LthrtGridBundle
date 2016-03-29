<?php

namespace Lthrt\GridBundle\Model\Maker;

use Doctrine\ORM\Query;
use Lthrt\GridBundle\Model\Grid\Grid;
use Lthrt\GridBundle\Model\Section\Body;
use Lthrt\GridBundle\Model\Section\Head;

class Maker
{
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;

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

    public function mapQueryBuilderPartials($qb)
    {
        // Map these and store final query in local field
        //
        $mapper  = new Mapper($this->em);
        $this->q = $mapper->mapQueryBuilderPartials($qb, $this->g);
        return $this;
    }

    public function init($opt = [], $attr = [])
    {
        $this->g = new Grid($opt, $attr);
        $this->g->addSection(new Head($opt, $attr));
        $this->g->addSection(new Body($opt, $attr));
    }

    public function hydrate($q = null)
    {
        if ($q) {
        } else {
            $q = $this->$q;
        }
        $results = $q->getResult(Query::HYDRATE_SCALAR);
        foreach ($results as $key => $result) {
            foreach ($result as $field => $value) {
                $_field = str_replace('.', '_', $field);
                if (isset($this->g->column[$_field])) {
                    $results[$key][$field] = $this->g->column[$_field]->getValue($value);
                } else {
                    unset($results[$key][$field]);
                }
            }
        }

        return $results;
    }
}
