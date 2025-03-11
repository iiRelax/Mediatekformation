<?php


namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategorieController
 *
 * @author Moi
 */
class AdminCategorieController extends AbstractController{
    
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response {
        $categories = $this->repository->findAll();
        return $this->render("admin/admin.categories.html.twig", ['categories' => $categories]);
    }
    
    private $repository;
    
    public function __construct(CategorieRepository $repository) {
        $this->repository = $repository;
    }
    
    #[Route('/admin/categories/suppr/{id}', name: 'admin.categories.suppr')]
    public function suppr($id): Response{
        $categorie = $this->repository->find($id);
        $formations = $this->repository->findAllForOne($id);        
        foreach($formations as $formation){
            if($formation){
                $message = 'Impossible de supprimer la catégorie, des formations y sont attachées';
                $this->addFlash('Erreur', $message);
                return $this->redirectToRoute('admin.categories');                            
            }         
        }
        $this->repository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
    
    #[Route('/admin/categories/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response{
        $nomCategorie = $request ->get("nom");
        $existingCategories = $this->repository->findOneBy(['name' =>$nomCategorie]);        
        
        if($existingCategories ) {
            $message = "Impossible de créer la catégorie, le nom de la catégorie existe déjà";
            $this->addFlash('Erreur', $message);
            return $this->redirectToRoute('admin.categories');
        }else{
            $categorie = new Categorie();
            $categorie ->setName($nomCategorie);
            $this ->repository ->add($categorie);
            return $this->redirectToRoute('admin.categories');            
        }
    }
    
}
