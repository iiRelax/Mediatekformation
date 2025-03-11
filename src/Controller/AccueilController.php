<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * Constantes pour les chemins et templates
     */
    private const ROUTE_ACCUEIL = '/';
    private const ROUTE_CGU = '/cgu';
    private const TEMPLATE_ACCUEIL = "pages/accueil.html.twig";
    private const TEMPLATE_CGU = "pages/cgu.html.twig";
    private const NOMBRE_FORMATIONS = 2;
    
    /**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     *
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }
    
    #[Route(self::ROUTE_ACCUEIL, name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(self::NOMBRE_FORMATIONS);
        return $this->render(self::TEMPLATE_ACCUEIL, [
            'formations' => $formations
        ]);
    }
    
    #[Route(self::ROUTE_CGU, name: 'cgu')]
    public function cgu(): Response{
        return $this->render(self::TEMPLATE_CGU);
    }
}
