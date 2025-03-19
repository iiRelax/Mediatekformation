<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Controller\admin;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Description of AdminCategoriesController
 *
 * @author zmuku
 */
class AdminCategoriesController extends AbstractController {
    
    /**
     * 
     * @var $categorieRepository
     */
    private $categorieRepository;
    
    /**
     * 
     * @param CategorieRepository $categorieRepository
     */
    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository= $categorieRepository;
    }
    
    /*
     * @var ADMIN_CATEGORIES_TWIG_PATH
     */
    private const ADMIN_CATEGORIES_TWIG_PATH = "admin/admin.categories.html.twig";
    
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    #[Route("/admin/categories", name:"admin.categories")]
    public function index(): Response{
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_CATEGORIES_TWIG_PATH, [
            'categories' => $categories
        ]);
    }
    
    
    /**
     * @Route("/admin/categorie/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    #[Route("/admin/categorie/suppr/{id}", name:"admin.categorie.suppr")]
    public function suppr(Categorie $categorie) : Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
    
    /**
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route("/admin/categorie/ajout", name:"admin.categorie.ajout")]
    public function ajout(Request $request): Response{
        $nomCategorie = $request->get("name");
        $categorie = new Categorie();
        $categorie->setName($nomCategorie);
        $this->categorieRepository->add($categorie, true);
        return $this->redirectToRoute('admin.categories');     
    }   
}

