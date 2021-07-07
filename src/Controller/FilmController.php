<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Form\ActeurType;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(FilmRepository $repo): Response
    {
        return $this->render('film/index.html.twig', [
           'films' => $repo->findAll(),
        ]);
    }
    /**
     * @Route("/ajouter-film", name="ajouter_film")
     */
    public function ajouter(Request $request,EntityManagerInterface $em): Response
    {
        $film = new Film();
        $formFilm = $this->createForm(FilmType::class,$film);
        $formFilm->handleRequest($request);
        $acteur = new Acteur();
        $formActeur = $this->createForm(ActeurType::class,$acteur);
        //$formActeur->handleRequest($request);
        if ($formFilm->isSubmitted()) {
            $nom    = $formFilm->get('nom')->getData();
            $prenom = $formFilm->get('prenom')->getData();
            // ajouter à la liste d'acteur ?
            $acteur = new Acteur();
            $acteur ->setNom($nom);
            $acteur ->setPrenom($prenom);

            $em->persist($acteur);
            $em->flush();
            
            $film->addActeur($acteur);
            $em->persist($film);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('film/ajouter.html.twig', [
          'formFilm' => $formFilm->createView(),
          'formActeur' => $formActeur->createView()
        ]);
    }

    /**
     * @Route("/ajouter-acteur", name="ajouter_acteur")
     */
    public function Acteur(Request $request,EntityManagerInterface $em): Response
    {
        $acteur = new Acteur();
        $formActeur = $this->createForm(ActeurType::class,$acteur);
        $formActeur->handleRequest($request);
        $em->persist($acteur);
        $em->flush();

            return $this->redirectToRoute('home');

      
    }

    /**
     * @Route("/edit-film/{id}", name="modifier_film")
     */
    public function modifier(Film $film, Request $request,EntityManagerInterface $em): Response
    {
       
        $formFilm = $this->createForm(FilmType::class,$film);
        $formFilm->handleRequest($request);

        if ($formFilm->isSubmitted()) {
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('film/modifier.html.twig', [
          'formFilm' => $formFilm->createView(),
         
        ]);
    }
}
