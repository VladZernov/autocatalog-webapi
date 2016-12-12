<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/showrooms", name="showrooms")
     * @Method({"GET"})
     */
    public function getShowrooms()
    {
        $em = $this->getDoctrine()->getManager();

        $showrooms = $em
            ->createQueryBuilder()
            ->select('s.id, s.title, s.address, s.phone')
            ->from('AppBundle:Showroom','s','s.id')
            ->getQuery()
            ->getResult();

        return $this->json($showrooms);
    }
    /**
     * @Route("/showroom", name="showroom")
     * @Method({"GET"})
     */
    public function getNearestShowroom() {}
    /**
     * @Route("/models/{showroom}", name="models", requirements={
     *     "showroom": "\d+"
     * })
     * @Method({"GET"})
     */
    public function getModels() {}
    public function markModel () {}
    public function unmarkModel() {}
    public function getMarkedModels() {}

}
