<?php

namespace Lthrt\GridBundle\Model\Maker;

use Doctrine\ORM\Query;
use Lthrt\GridBundle\Model\Cell\Cell;
use Lthrt\GridBundle\Model\Grid\Grid;
use Lthrt\GridBundle\Model\Row\Row;
use Lthrt\GridBundle\Model\Section\Body;
use Lthrt\GridBundle\Model\Section\Head;

class Maker
{
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;

    private $aliases;
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
        $this->g      = new Grid();
        $this->g->addSection(Grid::HEAD, new Head());
        $this->g->addSection(Grid::BODY, new Body());
    }

    public function hydrateFromQB($qb)
    {
        $mapper        = new Mapper($this->em);
        $map           = $mapper->mapQueryBuilder($qb, $this->g);
        $this->q       = $map['q'];
        $this->aliases = $map['aliases'];
        $results       = $this->q->getResult(Query::HYDRATE_SCALAR);

        foreach ($results as $key => $result) {
            if (0 == $key) {
                $header = new Row();
            }
            $row = new Row();
            foreach ($result as $field => $value) {
                $class = strstr($field, "__", true);
                $rest  = substr(strstr($field, "__"), 2);
                $alias = strstr(substr(strstr($field, "__"), 2), '_', true);
                $prop  = substr(strstr(substr(strstr($field, "__"), 2), '_'), 1);
                if (isset($this->g->column[$field])) {
                    $cell       = new Cell();
                    $cell->opt  = ['value' => $this->g->column[$field]->getValue($value)];
                    $cell->attr = ['class' => 'grid_cell'];
                    $cell->attr = ['entity_id' => $result[
                        $class . '__' . $alias . '_' . "id"],
                    ];
                    $row->addCell($cell);
                    $results[$key][$field] = $this->g->column[$field]->getValue($value);
                    if (0 == $key) {
                        $hCell       = new Cell(['tag' => 'th']);
                        $hCell->opt  = ['header' => $this->g->column[$field]->getOpt('header')];
                        $hCell->attr = ['class' => 'grid_header'];
                        $hCell->attr = ['entity_class' => $class];
                        $hCell->attr = ['entity_prop' => $prop];
                        $header->addCell($hCell);
                    }
                } else {
                    unset($results[$key][$field]);
                }
            }
            $this->g->getBody()->addRow($row);
        }
        $this->g->getHead()->addRow($header);
        print_r($this->g->html());
    }
}
