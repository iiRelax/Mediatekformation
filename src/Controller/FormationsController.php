<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    /**
     * Constantes pour les chemins et templates
     */
    private const ROUTE_FORMATIONS = '/formations';
    private const ROUTE_FORMATIONS_TRI = '/formations/tri/{champ}/{ordre}/{table}';
    private const ROUTE_FORMATIONS_RECHERCHE = '/formations/recherche/{champ}/{table}';
    private const ROUTE_FORMATIONS_SHOWONE = '/formations/formation/{id}';
    
    private const TEMPLATE_FORMATIONS = "pages/formations.html.twig";
    private const TEMPLATE_FORMATION = "pages/formation.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;
    
        
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
        
    }
    
    #[Route(self::ROUTE_FORMATIONS, name: 'formations')]
    public function index(): Response{        
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();        
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories            
        ]);
    }

    #[Route(self::ROUTE_FORMATIONS_TRI, name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route(self::ROUTE_FORMATIONS_RECHERCHE, name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route(self::ROUTE_FORMATIONS_SHOWONE, name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render(self::TEMPLATE_FORMATION, [
            'formation' => $formation
        ]);
    }    
    
}
