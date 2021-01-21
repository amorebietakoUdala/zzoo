<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\AppBundle;
use AppBundle\Entity\Ordenantza;
use AppBundle\Entity\Udala;

class DefaultController extends Controller
{
     /**
     * @Route("/ordenantzak/{udala}/{_locale}/", name="ordenantzakList", 
     *    requirements={
     *           "_locale": "eu|es"
     * })
     */
    public function ordenantzakListAction(Request $request, $udala)
    {
        return $this->render('default\list.html.twig', array(
            'udala' => $udala
        ));
    }

    /**
     * @Route("/ordenantza/{id}", name="ordenantzabat")
     */
    public function ordenantzabatAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AppBundle\Entity\Ordenantza $ordenantza */
        $ordenantza = $em->getRepository('AppBundle:Ordenantza')->find($id);

        /** @var \AppBundle\Entity\Udala $udala */
        $udala = $ordenantza->getUdala();

        return $this->render('default\ordenantza.html.twig', array(
            'ordenantza' => $ordenantza,
            'udala' => $udala
        ));
    }

    /**
     * @Route("/html/{udala}", name="homepage")
     */
    public function htmlAction(Request $request, $udala)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $udala \AppBundle\Entity\Udala */
        $udala = $em->getRepository('AppBundle:Udala')->findOneBy(array("kodea" => $udala));

        /** @var $ordenantzak \AppBundle\Entity\Ordenantza */
        $ordenantzak = $em->getRepository('AppBundle:Ordenantza')->findBy(
            array(
                'udala' => $udala->getId(),
            )
            , array('kodea' => 'ASC')
        );

        return $this->render('default\index.html.twig', array(
            'ordenantzas' => $ordenantzak,
            'udala' => $udala,
        ));
    }
}
