<?php


namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistController
 *
 * @author Moi
 */
class AdminPlaylistController extends AbstractController{
    
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response {
        $playlists= $this->repository->findAllOrderByName('ASC');
        return $this->render("admin/admin.playlists.html.twig", ['playlists' => $playlists]);
    }
    
    private $repository;
    private $formationRepository;    
    
    public function __construct(PlaylistRepository $repository, FormationRepository $formationRepository) {
        $this->repository = $repository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('admin/playlist/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr($id, Formation $idPlaylist): Response {
        $playlist = $this->repository->find($id);
        $nbFormation = $this->formationRepository->nbFormationByOnePlaylist($id);
        if($nbFormation == 0){
            $this->repository->remove($playlist);
        }else{
            $message = 'Impossible de supprimer la playlist, des formations y sont attachées';
            $this->addFlash('Erreur', $message);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->redirectToRoute('admin.playlists');
    }
    
    #[Route('admin/playlist/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->repository->add($playlist);
            return $this->render("admin/admin.playlist.ajout.html.twig", ['playlist' => $playlist, 
            'formplaylist' => $formPlaylist->createView()]);
        }        
        return $this->render("admin/admin.playlist.ajout.html.twig", ['playlist' => $playlist, 
            'formplaylist' => $formPlaylist->createView()]);
    }
    
    #[Route('admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->repository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->repository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }        
        return $this->render("admin/admin.playlist.editer.html.twig", ['playlist' => $playlist, 
            'formplaylist' => $formPlaylist->createView()]);
    }
    
    #[Route('admin/playlists/tri/{champ}/{ordre}/{table}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $playlists = $this->repository->findAllOrderByName($ordre);
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists
        ]);
    }
    
    #[Route('admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{        
        $valeur = $request->get("recherche");
        $playlists = $this->repository->findByContainValue($champ, $valeur, $table);        
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'valeur' => $valeur,
            'table' => $table
        ]);        
    }
    
}
