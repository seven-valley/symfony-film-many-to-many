<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Personne;
use App\Form\EquipeType;
use App\Form\PersonneType;
use App\Repository\EquipeRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EquipeRepository $repoEquipe,PersonneRepository $repoPersonne): Response
    {
        $equipe = new Equipe();
        $formEquipe = $this->createForm(EquipeType::class,$equipe);

        $personne = new Personne();
        $formPersonne = $this->createForm(PersonneType::class,$personne);

        return $this->render('team/index.html.twig', [
            'formEquipe'    => $formEquipe->createView(),
            'formPersonne'  => $formPersonne->createView(),
            'equipes'       => $repoEquipe->findAll(),
            'personnes'     => $repoPersonne->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-equipe/", name="equipe_ajouter")
     */
    public function ajouterEquipe(Request $request,EntityManagerInterface $em): Response
    {
        $equipe = new Equipe();
        $formEquipe = $this->createForm(EquipeType::class,$equipe);
        $formEquipe->handleRequest($request); // on vient hydrater
        $em->persist($equipe);
        $em->flush();
        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/enlever-equipe/{id}", name="equipe_enlever")
     */
    public function enleverEquipe(Equipe $equipe,EntityManagerInterface $em): Response
    {
        $em->remove($equipe);
        $em->flush();
        return $this->redirectToRoute('home');
    }

     /**
     * @Route("/ajouter-personne/", name="personne_ajouter")
     */
    public function ajouterPersonne(Request $request,EntityManagerInterface $em): Response
    {
        $personne = new Personne();
        $formPersonne = $this->createForm(PersonneType::class,$personne);
        $formPersonne->handleRequest($request); // on vient hydrater
        // recuperer le contenu de select : equipes
        $equipe =$formPersonne->get('equipes')->getData();
        if ($equipe)
            $personne->addEquipe($equipe);
        $em->persist($personne);
        $em->flush();
        return $this->redirectToRoute('home');
    }
 /**
     * @Route("/enlever-personne/{personne}", name="personne_enlever")
     */
    public function enleverPersonne(Personne $personne,EntityManagerInterface $em): Response
    {
        $em->remove($personne);
        $em->flush();
        return $this->redirectToRoute('home');
    }
     /**
     * @Route("/enlever-personne-equipe/{equipe}/{personne}", name="equipe_enlever_personne")
     */
    public function enleverPersonneEquipe(Equipe $equipe,Personne $personne,
    EntityManagerInterface $em): Response
    {
       //dump ($personne->getPrenom()." ".$personne->getNom());
       //dd($equipe->getNom());
       //$equipe->removePersonne($personne);
       $personne->removeEquipe($equipe);
       //$em->persist($equipe);
       $em->flush();
       //dd($equipe);
       return $this->redirectToRoute('home');
    }

    /**
     * @Route("/ajouter-personne-equipe/{equipe}/{personne}", name="equipe_ajouter_personne")
     */
    public function ajouterPersonneEquipe(Equipe $equipe,Personne $personne,
    EntityManagerInterface $em): Response
    {
     
       $personne->addEquipe($equipe);
       $em->flush();
       $tab['message'] ='ok';
       return $this->json($tab);
       // return $this->redirectToRoute('home');
    }

}
