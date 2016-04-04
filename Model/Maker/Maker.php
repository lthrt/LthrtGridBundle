<?php

namespace Lthrt\GridBundle\Model\Maker;

use Doctrine\ORM\Query;
use Lthrt\GridBundle\Model\Cell\Cell;
use Lthrt\GridBundle\Model\Column\Column;
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
    private $results;
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

    public function rawFromQuery($q)
    {
        // creates own column config
        // this cannot easily rewrite the query, so the path names and such like
        // will have to be manually done, probably in a twig template
        $this->results = $q->getResult(Query::HYDRATE_SCALAR);
        $this->g->clearColumns();
        foreach ($this->results as $key => $result) {
            if (0 == $key) {
                $header = new Row();
            }
            $row = new Row();
            foreach ($result as $field => $value) {
                if (!isset($this->g->column[$field])) {
                    $this->g->addColumn($field, new Column());
                }
                $cell       = new Cell();
                $cell->opt  = ['value' => $this->g->column[$field]->getValue($value)];
                $cell->attr = ['class' => 'grid_cell'];
                $row->addCell($cell);
                $results[$key][$field] = $this->g->column[$field]->getValue($value);
                if (0 == $key) {
                    $hCell       = new Cell(['tag' => 'th']);
                    $hCell->opt  = ['header' => substr(strstr($field, '__'), 2)];
                    $hCell->attr = ['class' => 'grid_header'];
                    $header->addCell($hCell);
                }
            }
            $this->g->getBody()->addRow($row);
        }
        $this->g->getHead()->addRow($header);
    }

    public function initFromQB($qb)
    {
        $mapper        = new Mapper($this->em);
        $map           = $mapper->mapQueryBuilder($qb, $this->g);
        $this->q       = $map['q'];
        $this->aliases = $map['aliases'];
        $this->results = $this->q->getResult(Query::HYDRATE_SCALAR);
    }

    public function rawFromQB($qb)
    {
        // creates own column config
        // this cannot easily rewrite the query, so the path names and such like
        // will have to be manually done, probably in a twig template
        $this->initFromQB($qb);
        $this->g->clearColumns();
        foreach ($this->results as $key => $result) {
            if (0 == $key) {
                $header = new Row();
            }
            $row = new Row();
            foreach ($result as $field => $value) {
                if (!isset($this->g->column[$field])) {
                    $this->g->addColumn($field, new Column());
                }
                $cell       = new Cell();
                $cell->opt  = ['value' => $this->g->column[$field]->getValue($value)];
                $cell->attr = ['class' => 'grid_cell'];
                $row->addCell($cell);
                $results[$key][$field] = $this->g->column[$field]->getValue($value);
                if (0 == $key) {
                    $hCell       = new Cell(['tag' => 'th']);
                    $hCell->opt  = ['header' => substr(strstr($field, '__'), 2)];
                    $hCell->attr = ['class' => 'grid_header'];
                    $header->addCell($hCell);
                }
            }
            $this->g->getBody()->addRow($row);
        }
        $this->g->getHead()->addRow($header);
    }

    public function hydrateFromQB($qb)
    {
        $this->initFromQB($qb);
        foreach ($this->results as $key => $result) {
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
                    $cell->attr = ['id' => $class . '__' . $result[
                        $class . '__' . $alias . '_' . "id"]];
                    $cell->attr = ['class' => trim('grid-cell ' . $this->g->column[$field]->getOpt('cellAttr') ?: '')];
                    $cell->attr = ['data-entity-id' => $result[
                        $class . '__' . $alias . '_' . "id"],
                    ];
                    $row->addCell($cell);
                    $results[$key][$field] = $this->g->column[$field]->getValue($value);
                    if (0 == $key) {
                        $hCell       = new Cell(['tag' => 'th']);
                        $hCell->opt  = ['header' => $this->g->column[$field]->getOpt('header')];
                        $hCell->attr = ['class' => 'grid-header'];
                        $hCell->attr = ['data-entity-class' => $class];
                        $hCell->attr = ['data-entity-property' => $prop];
                        $header->addCell($hCell);
                    }
                } else {
                    unset($results[$key][$field]);
                }
            }
            $this->g->getBody()->addRow($row);
        }
        $this->g->getHead()->addRow($header);
    }
}
