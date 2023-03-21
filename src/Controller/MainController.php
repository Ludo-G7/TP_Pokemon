<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Form\PokedexType;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/recherche/capture', name:'main_search_capture')]
    public function rechercheByCapture(PokedexRepository $pokedexRepository):Response
    {
        $rechercheByCapture = $pokedexRepository->findBy(['is_captured'=>1], []);

        return $this -> render('main/pokedex.html.twig',
        [
            'poke' => $rechercheByCapture
        ]
        );
    }

    #[Route('/recherche/nom', name:'main_search_nom')]
    public function rechercheByNom(PokedexRepository $pokedexRepository):Response
    {
        $rechercheByNom = $pokedexRepository->findBy([], ['name'=>'ASC']);

        return $this -> render('main/pokedex.html.twig',
            [
                'poke' => $rechercheByNom
            ]
        );
    }

    #[Route('/Ajout', name:'main_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entityManager){
        $pokemon = new Pokedex();
        $pokemonForm = $this->createForm(PokedexType::class, $pokemon);
        dump($pokemon);
        $pokemonForm->handleRequest($request);

        if($pokemonForm->isSubmitted() && $pokemonForm->isValid()){
            $pokemon->setis_captured(false);
            $entityManager->persist($pokemon);
            $entityManager->flush();

            $this->addFlash('success', 'Pokemon Ajouté !!!');
            return $this->redirectToRoute('main_details', ['id'=>$pokemon->getId()]);
        }

        return $this->render('main/ajout.html.twig', [
            'pokemonform' => $pokemonForm->createView()
        ]);
    }


    #[Route('/capture/{id}', name:'main_capture')]
    public function capture(int $id, EntityManagerInterface $entityManager, PokedexRepository $pokedexRepository ):Response
    {
        $pokemon = $pokedexRepository->find($id);
        $pokemon ->setIs_captured(!$pokemon->isIs_captured());
        /*$pokemon->setis_captured(!$pokemon->isis_captured());*/
        $entityManager->persist($pokemon);
        $entityManager->flush();
        return $this -> redirectToRoute('main_pokedex');
    }



}
