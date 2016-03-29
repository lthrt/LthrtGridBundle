<?php
namespace Lthrt\GridBundle\Model;

// use Knp\Component\Pager\Paginator;

class Dumper
{

    private $twig;

    /**
     * Dependency injection constructor.
     *
     * @param Doctrine Service
     * @param Router Service
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * This should never be used -- method is so there is not an exception thrown.
     *
     * @return string
     */
    public function __toString()
    {
        return "Grid Dumper-- Don't print this";
    }

    /**
     * Get twig
     *
     * @return
     */

    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Set twig
     *
     * @param
     * @return $this
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
        return $this;
    }

    public function dumpResults($results)
    {
        $twig = $this->getTwig();
        // print_r("<br><br><pre>");
        // var_dump(get_class_methods($twig));
        // die;
        return $twig->render('LighthartGridBundle:Dump:results.html.twig', ['results' => $results]);
    }

    public function dumpDQL($qb)
    {
        $twig = $this->getTwig();
        return $twig->render('LighthartGridBundle:Dump:dql.html.twig', ['qb' => $qb]);
    }

    public function dumpSQL($qb)
    {
        $twig = $this->getTwig();
        return $twig->render('LighthartGridBundle:Dump:sql.html.twig', ['qb' => $qb]);
    }
}
