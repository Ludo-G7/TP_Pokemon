<?php

namespace App\Controller;

use App\Repository\PokedexRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }


    #[Route('/list', name:'main_pokedex')]
    public function pokedex(PokedexRepository $pokedexRepository):Response
    {
        $pokelist = $pokedexRepository->findAll();
        return $this->render('main/pokedex.html.twig',
            [
                'poke'=>$pokelist
            ]);
    }

    #[Route('/details/{id}', name:'main_details')]
    public function details(int $id, PokedexRepository $pokedexRepository):Response
    {
        //Todo : chercher la série en fonction de son identifiant dans la BDD
        $detailPok = $pokedexRepository->find($id);

        return $this->render('main/details.html.twig',
            [
                //TODO : passer la série à twig
                'detailPok' => $detailPok
            ]);
    }

    #[Route('/recherche', name:'main_search_capture')]
    public function rechercheByCapture(PokedexRepository $pokedexRepository):Response
    {
        $rechercheByCapture = $pokedexRepository->findBy(['is_captured'=>1], []);

        return $this -> render('main/pokedex.html.twig',
        [
            'poke' => $rechercheByCapture
        ]
        );
    }

    #[Route('/recherche2', name:'main_search_nom')]
    public function rechercheByNom(PokedexRepository $pokedexRepository):Response
    {
        $rechercheByNom = $pokedexRepository->findBy([], ['name'=>'ASC']);

        return $this -> render('main/pokedex.html.twig',
            [
                'poke' => $rechercheByNom
            ]
        );
    }

    /*
    #[Route('/capture', name:'main_capture')]
    public function capture(PokedexRepository $pokedexRepository):Response
    {
        $capture = $pokedexRepository->;

        return $this -> render('main/pokedex.html.twig',
            [
                'poke' => $rechercheByNom
            ]
        );
    }
*/


}
