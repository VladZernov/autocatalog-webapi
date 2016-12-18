<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
class DefaultController extends Controller
{
    /**
     * @Route("/showrooms", name="showrooms")
     * @Method({"GET"})
     */
    public function getShowrooms(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $showrooms = $em
            ->createQueryBuilder()
            ->select('s.id, s.title, s.address, s.phone')
            ->from('AppBundle:Showroom','s')
            ->getQuery()
            ->getResult();
        $callback = $request->get('callback');  
        $response = new JsonResponse($showrooms, 200, array());
        $response->setCallback($callback);
        return $response;
    }
    /**
     * @Route("/showroom", name="showroom")
     * @Method({"GET"})
     */
    public function getNearestShowroom(Request $request) {

        $x = $request->get('x');
        $y = $request->get('y');

        $em = $this->getDoctrine()->getManager();

        $nearestShowroom = $em
            ->createQueryBuilder()
            ->select('s.id, s.title, s.address, s.phone, sqrt(pow(s.longtitude - :x, 2) + pow(s.latitude - :y, 2)) as distance')
            ->from('AppBundle:Showroom','s')
            ->orderBy('distance','asc')
            ->setMaxResults(1)
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->getQuery()
            ->getSingleResult();

        return $this->json($nearestShowroom);
    }
    /**
     * @Route("/models/{showroom}", name="models", requirements={
     *     "showroom": "\d+"
     * })
     * @Method({"GET"})
     */
    public function getModels($showroom) {
        $em = $this->getDoctrine()->getManager();

        $cars = $em
            ->createQueryBuilder()
            ->select('c.model, c.price')
            ->from('AppBundle:Car','c','c.id')
            ->innerJoin('c.showrooms', 's')            
            ->where('s.id = :showroom_id')
            ->setParameter('showroom_id', $showroom)
            ->getQuery()
            ->getResult();

        return $this->json($cars);        
    }
    public function markModel () {}
    public function unmarkModel() {}
    public function getMarkedModels() {}

}
