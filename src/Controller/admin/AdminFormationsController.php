<?php
namespace App\Controller\admin;
use App\Entity\Formation;
use App\Form\FormationType;
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
class AdminFormationsController extends AbstractController {
    private $test;
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
    
    /**
     * 
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    #[Route('/admin/formations', name:'admin.formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
        
        
    /*
     * @var ADMIN_FORMATIONS_TWIG_PATH
     */
    private const ADMIN_FORMATIONS_TWIG_PATH = "admin/admin.formations.html.twig";
    /**
     * @Route("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    #[Route('/admin/formation/suppr/{id}', name:'admin.formation.suppr')]
    public function suppr(Formation $formation) {
        $titre = $formation->getTitle();
        $this->formationRepository->remove($formation, true);
        $this->addFlash('success', 'La suppression de la formation "' . $titre . '" a été effectuée avec succès.');
        return $this->redirectToRoute('admin.formations');
    }
    
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formation/ajout', name:'admin.formation.ajout')]
    public function ajout(Request $request) : Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            $this->addFlash('success', 'La formation "' . $formation->getTitle() . '" a été ajoutée avec succès.');
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.ajout.html.twig", 
            ['formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formation/edit/{id}', name:'admin.formation.edit')]
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            $this->addFlash('success', 'La formation "' . $formation->getTitle() . '" a été modifiée avec succès.');
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig", 
            ['formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name:'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name:'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/admin/formations/formation/{id}", name="admin.formations.showone")
     * @param type $id
     * @return Response
     */
    #[Route('/admin/formations/formation/{id}', name:'admin.formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}