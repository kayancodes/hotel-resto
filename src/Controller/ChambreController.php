<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/chambre')]
class ChambreController extends AbstractController
{
    private $em;

    //on va mettre ENTITY MANAGER dans construct comme on va l'utiliser plusieurs fois 
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    // ---------- DEBUT CRUD - CREATE - AJOUTER -------------//
    #[Route('/new', name: 'chambre_new')]
    public function new(Request $request): Response
    {

        //creation de la chambre
        $chambre = new Chambre();

        //creation du formulaire 
        $form = $this->createForm(ChambreType::class, $chambre);

        //association du formulaire 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //recuperer le fichier
            //le nommer le deplacer

            /////////// DEBUT GESTION
            //1ere possibilite
            //$file = $request->files->get("chambre")["photo"];

            //2eme possibilitedd
            //on lui dans notre formulaire on a un champ qui s'appel photo, recupere moi les données
            $file = $form["photo"]->getData();
            //--> on recupere le contenu de notre champ file


            //METTRE EN COMMENTAIRE POUR LA MISE EN LIGNE SUR HOROKU
            if ($file) {
                //on va aussi recupere le chemin du dossier upload
                $dossier_upload = $this->getParameter("upload_directory");
                $photo = md5(uniqid()) . "." . $file->getClientOriginalExtension(); //.jpg

                //ici on a recuperé tout ce qu'il fallait 
                //on a plus qu'a deplacer le fichier
                $file->move($dossier_upload, $photo);

                //On recupere le nom du fichier
                $chambre->setPhoto($photo);
            }


            //ici la date de creation sera AUJOURDHUI
            $chambre->setCreatedAt(new \DateTimeImmutable("now"));
            //ici on lui dit prend la chambre qui a ete remplis et insere le en BASE DE DONNEE
            $this->em->persist($chambre);
            $this->em->flush();
            return $this->redirectToRoute("chambre_list");
        };


        // on envoi le formulaire a la vue 
        return $this->render("chambre/new.html.twig", [
            "form" => $form->createView()
        ]);
    }
    // ---------- FIN CRUD - CREATE - AJOUTER -------------//




    // ---------- DEBUT CRUD - READ - AFFICHER -------------//
    #[Route('/list', name: 'chambre_list')]
    public function list(): Response
    {
        //on selectionne toutes les Chambres 
        $chambres = $this->em->getRepository(Chambre::class)->findAll();
        return $this->render("chambre/list.html.twig", ["chambres" => $chambres]);
    }
    // ---------- FIN CRUD - READ - AFFICHER -------------//



    // ---------- DEBUT CRUD - DELETE - SUPPRIMER -------------//
    #[Route('/supp/{id}', name: 'chambre_suppr')]
    public function suppr($id): RedirectResponse
    {

        $chambreASupprimer = $this->em->getRepository(Chambre::class)->find($id);

        if ($chambreASupprimer) {
            //on va aussi recupere le chemin du dossier upload
            $dossier_upload = $this->getParameter("upload_directory");
            //On recupere la photo de la chambre a supprimer
            $photo = $chambreASupprimer->getPhoto();

            unlink($dossier_upload . "/" . $photo); // Suppresion physique de la photo dans le dossier upload

            $this->em->remove($chambreASupprimer);
            $this->em->flush();
        }
        return $this->redirectToRoute("chambre_list");
    }
    // ---------- FIN CRUD - DELETE - SUPPRIMER -------------//


    // ---------- DEBUT CRUD - UPDATE - MODIFIER -------------//
    #[Route('/update/{id}', name: 'chambre_update')]
    public function update(Request $request, $id): Response
    {
        //creation de la chambre
        $chambre = $this->em->getRepository(Chambre::class)->find($id);

        if ($chambre === null) return $this->redirectToRoute("chambre_list");


        //creation du formulaire 
        $form = $this->createForm(ChambreType::class, $chambre);

        //association du formulaire 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //recuperer le fichier
            //le nommer le deplacer

            /////////// DEBUT GESTION
            //1ere possibilite
            //$file = $request->files->get("chambre")["photo"];

            //2eme possibilitedd
            //on lui dans notre formulaire on a un champ qui s'appel photo, recupere moi les données
            $file = $form["photo"]->getData();
            //--> on recupere le contenu de notre champ file


            //METTRE EN COMMENTAIRE POUR LA MISE EN LIGNE SUR HOROKU
            if ($file) {
                //on va aussi recupere le chemin du dossier upload
                $dossier_upload = $this->getParameter("upload_directory");
                $photo = md5(uniqid()) . "." . $file->getClientOriginalExtension(); //.jpg

                //ici on a recuperé tout ce qu'il fallait 
                //on a plus qu'a deplacer le fichier
                $file->move($dossier_upload, $photo);

                //On recupere le nom du fichier
                $chambre->setPhoto($photo);
            }


            //ici la date de creation sera AUJOURDHUI
            $chambre->setCreatedAt(new \DateTimeImmutable("now"));
            //ici on lui dit prend la chambre qui a ete remplis et insere le en BASE DE DONNEE
            $this->em->persist($chambre);
            $this->em->flush();
            return $this->redirectToRoute("chambre_list");
        };


        // on envoi le formulaire a la vue 
        return $this->render("chambre/new.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
