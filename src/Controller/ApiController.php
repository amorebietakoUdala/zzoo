<?php

namespace App\Controller;

use App\Entity\Atala;
use App\Entity\Azpiatala;
use App\Entity\Kontzeptua;
use App\Entity\Ordenantza;
use App\Entity\Udala;
use Doctrine\ORM\EntityManagerInterface;
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

    private $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;    
    }
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
    public function getOrdenantzakbykodeaAction(Request $request, $kodea)
    {
        $_format = $request->get('_format');
        $ordenantzak = $this->em->getRepository(Ordenantza::class)->getOrdenantzakByUdalKodea($kodea);
        return $this->returnResponseDataAsFormat($ordenantzak, $_format);
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
    public function getOrdenantzakAction(Request $request, $udalaid)
    {
        $_format = $request->get('_format','json');
        $ordenantzak = $this->em->getRepository(Ordenantza::class)->findBy(['udala' => $udalaid]);
        $udala = $this->em->getRepository(Udala::class)->find($udalaid);
        return $this->returnResponseDataAsFormat($ordenantzak,$_format,'default/index.html.twig',[
            'ordenantzas' => $ordenantzak,
            'udala' => $udala
        ]);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza baten informazioa eskuratu"
     * )
     *
     * @Annotations\View()
     * @Get("/ordenantza/{id}")
     * 
     */
    public function getOrdenantzaAction(Request $request, $id)
    {
        $_format = $request->get('_format');
        $ordenantza = $this->em->getRepository(Ordenantza::class)->find($id);
        return $this->returnResponseDataAsFormat($ordenantza, $_format, 'default/ordenantza.html.twig', [
            'ordenantza' => $ordenantza
        ]);
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
    public function getAtalakAction(Request $request, $ordenantzaid)
    {
        $_format = $request->get('_format','json');
        $atalak = $this->em->getRepository(Atala::class)->getAtalakByOrdenantzaId($ordenantzaid);
        return $this->returnResponseDataAsFormat($atalak, $_format);
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
    public function getAtalaAction(Request $request, $id)
    {
        $_format = $request->get('_format','json');   
        $atala = $this->em->getRepository(Atala::class)->find($id);
        return $this->returnResponseDataAsFormat($atala, $_format);
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
    public function getAzpiatalakudalaAction(Request $request, $udalaid)
    {
        $_format = $request->get('_format','json');
        $azpiatalak = $this->em->getRepository(Azpiatala::class)->getAzpiatalaByUdalaId($udalaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
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
    public function getAzpiatalakAction(Request $request, $tributuaid)
    {
        $_format = $request->get('_format','json');
        $azpiatalak = $this->em->getRepository(Azpiatala::class)->getAzpiatalaByAtalaId($tributuaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
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
    public function getAzpiatalaAction(Request $request, $id)
    {
        $_format = $request->get('_format','json');
        $azpiatala = $this->em->getRepository(Azpiatala::class)->find($id);
        return $this->returnResponseDataAsFormat($azpiatala, $_format);
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
    public function getKontzeptuaAction($id, $_format = "json")
    {
        
        /** @var Kontzeptua $kontzeptua */
        $kontzeptua = $this->em->getRepository(Kontzeptua::class)->find($id);
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
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
    public function getExamPricesAction($kodea, $_format = "json")
    {
        /* 'Tasas según grupo azpiatalaren kodea azterketen prezioak bilatzeko
         * Gero erreziboen aplikazioan helbidea ezartzen da kontzeptu bakoitzeko
         * eta behar den zenbatekoa itzultzen du. Zenbatekoa baino ez du itzultzen.
         */
        $azterketaAzpiatala = $this->container->getParameter('azterketa_azpiatala');
        $kontzeptua = $this->em->getRepository(Kontzeptua::class)->findOneBy([
            'azpiatala' => $azterketaAzpiatala,
            'kodea_prod' => $kodea,
        ]);
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
    }

    private function returnResponseDataAsFormat($data, $_format = 'json', $template = null, $templateData = []) {
        $view = View::create();
        $view->setData($data);
        //dump($_format);die;
        if (null !== $_format && $_format === 'html') {
            $view->setTemplate($template);
            $view->setTemplateData($templateData);
            $view->setFormat('html');
        }
        if (null !== $_format && $_format === 'json') {
            $view->setFormat('json'); 
            $view->setHeaders([
                'content-type' => 'application/json; charset=utf-8',
                'access-control-allow-origin' => '*',
            ]);
        }
        return $view;
    }
}
