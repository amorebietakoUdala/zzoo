<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Atala;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * API.
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{
    /**
     * @Route("/", name="api")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('nelmio_api_doc_index', array(), 301);
    }

//    ORDENANTZAK

    /**
     * Udal baten Ordenantza zerrenda Udal-Kodea bidez.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return array|View
     * @Annotations\View()
     * @Get("/ordenantzakbykodea/{kodea}.{_format}")
     */
    public function getOrdenantzakbykodeaAction($kodea)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $query \Doctrine\DBAL\Query\QueryBuilder */
        $query = $em->createQuery( /* @lang text */
            '
            SELECT o 
            FROM AppBundle:Ordenantza o
               INNER JOIN AppBundle:Udala u
            WHERE u.kodea = :udalkodea AND ((o.ezabatu IS NULL) or (o.ezabatu <> 1))
	    ORDER BY o.kodea
            ');
        $query->setParameter('udalkodea', $kodea);
        $ordenantzak = $query->getResult();
        $view = View::create();
        $view->setData($ordenantzak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * Udal baten Ordenantza zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return array|View
     *
     * @Annotations\View()
     * @Get("/ordenantzakbyid/{udalaid}")
     */
    public function getOrdenantzakAction($udalaid)
    {
        $em = $this->getDoctrine()->getManager();
        $ordenantzak = $em->getRepository('AppBundle:Ordenantza')->findBy(
            array(
                'udala.kodea' => $udalaid,
            )
        );

        $view = View::create();
        $view->setData($ordenantzak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza baten informazioa eskuratu"
     * )
     *
     * @Annotations\View()
     * @Get("/ordenantza/{id}")
     */
    public function getOrdenantzaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ordenantza = $em->getRepository('AppBundle:Ordenantza')->findById($id);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $ordenantza;
    }

//    ATALAK

    /**
     * Ordenantza batem tributu guztien zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza baten tributu guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @param $ordenantzaid
     *
     * @return View
     * @Annotations\View()
     * @Get("/tributuak/{ordenantzaid}")
     */
    public function getAtalakAction($ordenantzaid)
    {
        $em = $this->getDoctrine()->getManager();
//        $atalak = $em->getRepository('AppBundle:Atala')->findBy(array('ordenantza'=>$ordenantzaid));
        /** @var $query \Doctrine\DBAL\Query\QueryBuilder */
        $query = $em->createQuery( /* @lang text */
            '
            SELECT a 
            FROM AppBundle:Atala a
               INNER JOIN a.ordenantza o
            WHERE o.id = :ordenantzaid AND ((a.ezabatu IS NULL) or (a.ezabatu <> 1))
            ');
        $query->setParameter('ordenantzaid', $ordenantzaid);
        $atalak = $query->getResult();

        $view = View::create();
        $view->setData($atalak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Tributu baten informazioa eskuratu"
     * )
     *
     * @Annotations\View()
     * @Get("/tributua/{id}")
     */
    public function getAtalaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $atala = $em->getRepository('AppBundle:Atala')->findById($id);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $atala;
    }

//    AZPIATALAK

    /**
     * Udal baten zergen zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten zerga guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/udalzergak/{udalaid}")
     */
    public function getAzpiatalakudalaAction($udalaid)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $query \Doctrine\DBAL\Query\QueryBuilder */
        $query = $em->createQuery(
            /* @lang text */
            'SELECT p.id, p.kodea_prod, p.izenburuaeu_prod, p.izenburuaes_prod 
                FROM AppBundle:Azpiatala p 
                WHERE p.udala=:udalaid AND ((p.ezabatu IS NULL) or (p.ezabatu <> 1))
                '
        );
        $query->setParameter('udalaid', $udalaid);
        $azpiatalak = $query->getResult();

        $view = View::create();
        $view->setData($azpiatalak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * Udal baten zergen zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten zerga guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @param $tributuaid
     *
     * @return View
     * @Annotations\View()
     * @Get("/zergak/{tributuaid}")
     */
    public function getAzpiatalakAction($tributuaid)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $query \Doctrine\DBAL\Query\QueryBuilder */
        $query = $em->createQuery( /* @lang text */
            '
                SELECT p.id, p.kodea_prod, p.izenburuaeu_prod,p.izenburuaes_prod  
                    FROM AppBundle:Azpiatala p 
                WHERE p.atala=:atalaid AND ((p.ezabatu IS NULL) or (p.ezabatu <> 1))
          ');
        $query->setParameter('atalaid', $tributuaid);
        $azpiatalak = $query->getResult();

        $view = View::create();
        $view->setData($azpiatalak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Zerga baten informazioa eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/zerga/{id}")
     */
    public function getAzpiatalaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $azpiatalak = $em->getRepository('AppBundle:Azpiatala')->findOneById($id);

        $view = View::create();
        $view->setData($azpiatalak);
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "kontzeptu baten zenbatekoa itzuli. Indarrean daudenak.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/kontzeptua/{id}.{_format}")
     */
    public function getKontzeptuaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $kontzeptua AppBundle\Entity\Kontzeptua */
        $kontzeptua = $em->getRepository('AppBundle:Kontzeptua')->find($id);

        $view = View::create();
        $view->setData(str_replace(',', '.', $kontzeptua->getKopuruaProd()));
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Azterketa kategoria baten azterketan parte hartzeko tasa itzuli.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/exam/{kodea}.{_format}")
     */
    public function getExamPricesAction($kodea)
    {
        /* 'Tasas según grupo azpiatalaren kodea azterketen prezioak bilatzeko
         * Gero erreziboen aplikazioan helbidea ezartzen da kontzeptu bakoitzeko
         * eta behar den zenbatekoa itzultzen du. Zenbatekoa baino ez du itzultzen.
         */
        $azterketaAzpiatala = $this->container->getParameter('azterketa_azpiatala');
        $em = $this->getDoctrine()->getManager();
        $kontzeptua = $em->getRepository('AppBundle:Kontzeptua')->findOneBy([
            'azpiatala' => $azterketaAzpiatala,
            'kodea_prod' => $kodea,
        ]);
        $view = View::create();
//        $view->setData(['price' => str_replace(',', '.', $kontzeptua->getKopuruaProd())]);
        $view->setData(str_replace(',', '.', $kontzeptua->getKopuruaProd()));
        header('content-type: application/json; charset=utf-8');
        header('access-control-allow-origin: *');

        return $view;
    }
}
