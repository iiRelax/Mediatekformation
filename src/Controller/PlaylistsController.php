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
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {
    
    /**
     * Constantes pour les chemins et templates
     */
    private const ROUTE_PLAYLISTS = '/playlists';
    private const ROUTE_PLAYLISTS_TRI = '/playlists/tri/{champ}/{ordre}';
    private const ROUTE_PLAYLISTS_RECHERCHE = '/playlists/recherche/{champ}/{table}';
    private const ROUTE_PLAYLISTS_SHOWONE = '/playlists/playlist/{id}';
    
    private const TEMPLATE_PLAYLISTS = "pages/playlists.html.twig";
    private const TEMPLATE_PLAYLIST = "pages/playlist.html.twig";
    
    private const CHAMP_NAME = "name";
    private const CHAMP_FORMATIONS = "formations";
    
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
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route(self::ROUTE_PLAYLISTS, name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');        
        $categories = $this->categorieRepository->findAll();        
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,             
            'categories' => $categories            
        ]);
    }

    #[Route(self::ROUTE_PLAYLISTS_TRI, name: 'playlists.sort')]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case self::CHAMP_NAME:
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case self::CHAMP_FORMATIONS:
                $playlists = $this->playlistRepository->findAllOrderByFormation($ordre);
                break;
            default:
                return $this->findAll();
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    #[Route(self::ROUTE_PLAYLISTS_RECHERCHE, name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route(self::ROUTE_PLAYLISTS_SHOWONE, name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::TEMPLATE_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }    
    
}
