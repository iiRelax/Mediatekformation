<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
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
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * @var string
     */
    private const FORMATIONS_TWIG_PATH = "pages/formations.html.twig";
    
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    public function __construct(FormationRepository $repository, CategorieRepository $categorieRepository) {
        $this->repository = $repository;
        $this->categorieRepository = $categorieRepository;
    }
    
    #[Route('/formations', name: 'formations')]
    public function index(): Response{
        $formations = $this->repository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->repository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}
