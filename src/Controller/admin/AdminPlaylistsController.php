<?php
namespace App\Controller\admin;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class AdminPlaylistsController extends AbstractController {
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
    
    /*
     * @var ADMIN_PLAYLISTS_TWIG_PATH
     */
    private const ADMIN_PLAYLISTS_TWIG_PATH = "admin/admin.playlists.html.twig";
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    #[Route("/admin/playlists", name:"admin.playlists")]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
     * @param Playlist $playlist
     * @return type
     */
    #[Route("/admin/playlist/suppr/{id}", name:"admin.playlist.suppr")]
    public function suppr(Playlist $playlist) {
        $nom = $playlist->getName();
        $this->playlistRepository->remove($playlist, true);
        $this->addFlash('success', 'La suppression de la playlist "' . $nom . '" a été effectuée avec succès.');
        return $this->redirectToRoute('admin.playlists');
    }
    
    /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route("/admin/playlist/ajout", name:"admin.playlist.ajout")]
    public function ajout(Request $request): Response {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            $this->addFlash('success', 'La playlist "' . $playlist->getName() . '" a été ajoutée avec succès.');
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/admin.playlist.ajout.html.twig",
            ['playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param Request $request
     * @param Playlist $playlist
     * @return Response
     */
    #[Route("/admin/playlist/edit/{id}", name:"admin.playlist.edit")]
    public function edit(Playlist $playlist, Request $request): Response {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            $this->addFlash('success', 'La playlist "' . $playlist->getName() . '" a été modifiée avec succès.');
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/admin.playlist.edit.html.twig",
            ['playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    /**
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route("/admin/playlists/tri/{champ}/{ordre}", name:"admin.playlists.sort")]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formations":
                $playlists = $this->playlistRepository->findAllOrderByFormations($ordre);
                break;
            default :
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          
	
    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route("/admin/playlists/recherche/{champ}/{table}", name:"admin.playlists.findallcontain")]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/admin/playlists/playlist/{id}", name="admin.playlists.showone")
     * @param type $id
     * @return Response
     */
    #[Route("/admin/playlists/playlist/{id}", name:"admin.playlists.showone")]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }       
    
}

