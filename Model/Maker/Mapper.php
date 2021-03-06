<?php

namespace Lthrt\GridBundle\Model\Maker;

class Mapper
{
    use \Lthrt\GridBundle\Model\Util\GetSetTrait;

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function mapQueryBuilder($qb, $g)
    {
        if (count($qb->getDqlPart('from')) > 1) {
            throw new \Exception('Grid must use querybuilder with single root alias.');
        }

        $this->em->getMetadataFactory()->getAllMetadata();

        // get class names from 'from' part of query

        $from                        = $qb->getDqlPart('from')[0];
        $aliases[$from->getAlias()]  = str_replace('\\', '_', $from->getFrom()) . '__' . $from->getAlias();
        $entities[$from->getAlias()] = $from->getFrom();

        // get class names from 'join' part of query
        $joins = $qb->getDqlPart('join');

        $mappings = [];

        if (isset($qb->getDqlPart('join')[$from->getAlias()])) {
            $joins = $qb->getDqlPart('join')[$from->getAlias()];
            foreach ($qb->getDqlPart('join')[$from->getAlias()] as $k => $join) {
                $field = substr(stristr($join->getJoin(), '.', false), 1);
                $alias = $join->getAlias();
                if (false === strpos($join->getJoin(), '\\')) {
                    $entity = stristr($join->getJoin(), '.', true);
                    if (!in_array($join->getAlias(), array_keys($aliases))) {
                        $mappings = $this->em->getMetadataFactory()
                            ->getMetadataFor($entities[$entity])
                            ->getAssociationMappings();
                        $entities[$join->getAlias()] = $mappings[$field]['targetEntity'];
                        $aliases[$join->getAlias()]  = str_replace('\\', '_', $entities[$join->getAlias()])
                        . '__' . $join->getAlias();
                    }
                } else {
                    // for backside joins
                    $entity = $join->getJoin();
                    if (!in_array($join->getAlias(), array_keys($aliases))) {
                        $mappings = $this->em->getMetadataFactory()
                            ->getMetadataFor($entity)
                            ->getAssociationMappings();
                        $entities[$join->getAlias()] = $entity;
                        $aliases[$join->getAlias()]  = str_replace('\\', '_', $entity)
                        . '__' . $join->getAlias();
                    }
                }
            };
        }

        // realias grid columns to match classpaths
        foreach ($g->column as $alias => $column) {
            $g->reAliasColumn($alias, $aliases[strstr($alias, '.', true)] . '_' . substr(strstr($alias, '.'), 1));
        }

        // index fields specified in grid controller
        $fields = [];
        foreach ($g->column as $alias => $column) {
            $newAlias            = strrev(substr(strstr(strrev($alias), '_'), 1));
            $newField            = strrev(strstr(strrev($alias), '_', true));
            $fields[$newAlias][] = $newField;
        }

        // group fields based on entity
        // id required for partials used next
        foreach ($g->column as $alias => $column) {
            $newAlias = strrev(substr(strstr(strrev($alias), '_'), 1));
            if (in_array('id', $fields[$newAlias])) {
            } else {
                array_unshift($fields[$newAlias], 'id');
            }
        }

        // convert to partials for smaller data retreival
        $qb->resetDqlPart('select');
        foreach ($fields as $alias => $properties) {
            $qb->addSelect('partial ' . $alias . '.{' . implode(',', $properties) . '}');
        }

        // convert query aliases to classpathnames
        $q = $qb->getQuery()->getDQL();
        foreach ($aliases as $aliasKey => $alias) {
            $q = str_replace(" " . $aliasKey . ".", " " . $aliases[$aliasKey] . ".", $q);
            $q = str_replace(" " . $aliasKey . " ", " " . $aliases[$aliasKey] . " ", $q);
            $q = str_replace(" " . $aliasKey . ", ", " " . $aliases[$aliasKey] . ", ", $q);
        }

        $q = $this->em->createQuery($q);

        return ['q' => $q, 'aliases' => $aliases];
    }
}
