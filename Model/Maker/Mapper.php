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

    // Mapping Aliases seems unnecessary
    public function mapQueryBuilder($qb, $g)
    {
        if (count($qb->getDqlPart('from')) > 1) {
            throw new \Exception('Grid must use querybuilder with single root alias.');
        }

        $this->em->getMetadataFactory()->getAllMetadata();

        $from                        = $qb->getDqlPart('from')[0];
        $aliases[$from->getAlias()]  = str_replace('\\', '_', $from->getFrom()) . '__' . $from->getAlias();
        $entities[$from->getAlias()] = $from->getFrom();

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

        // var_dump($g->column);
        foreach ($g->column as $alias => $column) {
            $g->reAliasColumn($alias, $aliases[strstr($alias, '.', true)] . '_' . substr(strstr($alias, '.'), 1));
        }

        $field = [];
        foreach ($g->column as $alias => $column) {
            $newAlias           = strstr($alias, '__', true);
            $newField           = substr(strstr($alias, '__'), 2);
            $field[$newAlias][] = substr(strstr($alias, '__'), 2);
        }

        foreach ($g->column as $alias => $column) {
            if (in_array('id', $field[strstr($alias, '__', true)])) {
            } else {
                array_unshift($field[strstr($alias, '__', true)], 'id');
            }
        }

        // $qb->resetDqlPart('select');
        // foreach ($field as $alias => $fields) {
        //     $qb->addSelect('partial ' . $alias . '.{' . implode(',', $fields) . '}');
        // }

        $q = $qb->getQuery()->getDQL();
        foreach ($aliases as $aliasKey => $alias) {
            $q = str_replace($aliasKey . ".", $aliases[$aliasKey] . ".", $q);
            $q = str_replace(" " . $aliasKey . " ", " " . $aliases[$aliasKey] . " ", $q);
            $q = str_replace(" " . $aliasKey . ", ", " " . $aliases[$aliasKey] . ", ", $q);
        }
        $q = $this->em->createQuery($q);

        return ['q' => $q, 'aliases' => $aliases];
    }
}
