<?php

namespace App\Controller;

use App\Entity\Ordenantza;
use App\Entity\Udala;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    private $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;    
    }

     /**
     * @Route("/ordenantzak/{udala}/{_locale}/", name="ordenantzakList", 
     *    requirements={
     *           "_locale": "eu|es"
     * })
     */
    public function ordenantzakListAction()
    {
        return $this->render('default\list.html.twig');
    }

    /**
     * @Route("/ordenantza/{id}", name="ordenantzabat")
     */
    public function ordenantzabatAction(Ordenantza $id)
    {
        /** @var Udala $udala */
        $udala = $id->getUdala();

        return $this->render('default\ordenantza.html.twig', array(
            'ordenantza' => $id,
            'udala' => $udala
        ));
    }

    /**
     * @Route("/html/{udala}", name="homepage")
     */
    public function htmlAction($udala)
    {
        
        /** @var Udala $udala */
        $udala = $this->em->getRepository(Udala::class)->findOneBy(array("kodea" => $udala));

        /** @var Ordenantza[] $ordenantzak */
        $ordenantzak = $this->em->getRepository(Ordenantza::class)->findBy(
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
