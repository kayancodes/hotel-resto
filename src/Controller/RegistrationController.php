<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Form\RegistrationFormType;
use App\Form\RegistrationFormUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    // ---------- DEBUT CRUD - CREATE - AJOUTER -------------//
    #[Route('/admin/membre/register', name: 'register_new')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        //on creer un utilisateur vide 
        $user = new User();
        //on associe cet utilisateur a notre registration form pour creer un formulaire 
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles([$form->get("role")->getData()]);


            //ici la date de creation sera AUJOURDHUI
            $user->setCreatedAt(new \DateTimeImmutable("now"));
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('chambre_list');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    // ---------- FIN CRUD - CREATE - AJOUTER -------------//



    // ---------- DEBUT CRUD - READ - AFFICHER -------------//
    #[Route('/admin/membre/list', name: 'register_list')]
    public function list(): Response
    {
        //requette pour recuperer ensemble des utilisateurs 
        //on veut recuperer la liste des USER 
        $membres = $this->em->getRepository(User::class)->findAll();

        return $this->render("registration/list.html.twig", [
            "membres" => $membres
        ]);
    }
    // ---------- FINT CRUD - READ - AFFICHER -------------//



    // ---------- DEBUT CRUD - UPDATE - MODIFIER  -------------//
    #[Route('/admin/membre/update{id}', name: 'register_update')]
    public function update($id, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $membreAModifier = $this->em->getRepository(User::class)->find($id);

        if ($membreAModifier === null) return $this->redirectToRoute("register_list");

        $form = $this->createForm(RegistrationFormUpdateType::class, $membreAModifier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('plainPassword')->getData() != "") {

                $membreAModifier->setPassword(
                    $userPasswordHasher->hashPassword(
                        $membreAModifier,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            if ($form->get("role")->getData() != "") {
                $membreAModifier->setRoles([$form->get("role")->getData()]);
            }



            //ici la date de creation sera AUJOURDHUI
            // $user->setCreatedAt(new \DateTimeImmutable("now"));
            $this->em->persist($membreAModifier);
            $this->em->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('chambre_list');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // ---------- FIN CRUD - UPDATE - MODIFIER  -------------//



    // ---------- DEBUT CRUD - DELETE - SUPPRIMER  -------------//
    #[Route('/admin/membre/suppr{id}', name: 'register_suppr')]
    public function suppr($id): RedirectResponse
    {

        $membreASupprimer = $this->em->getRepository(User::class)->find($id);

        if ($membreASupprimer) {

            // ------invocation de code -----//
            // $this->imgService->deleteImage($membreASupprimer);
            // ------invocation de code -----//

            $this->em->remove($membreASupprimer);
            $this->em->flush();
        }

        return $this->redirectToRoute("register_list");
    }
    // ---------- FIN CRUD - DELETE - SUPPRIMER  -------------//
}
