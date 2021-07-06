<?php

namespace App\Controller;

use App\Entity\Film;
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
     * @Route("/", name="film")
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
        $form = $this->createForm(FilmType::class,$film);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // recupérer le tableau et le traiter
            //$tab = $form->get('acteurs')->getData();
            //dd($tab);
            $em->persist($film);
            $em->flush();

            return $this->redirectToRoute('film');
        }
        return $this->render('film/ajouter.html.twig', [
          'formFilm' => $form->createView(),
        ]);
    }
}
