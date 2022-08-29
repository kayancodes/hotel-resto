<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/', name: "home_index")]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }



    #[Route('/home', name: "home")]
    public function home(): Response
    {
        return $this->render('front/home.html.twig');
    }




    #[Route('/chambre', name: "chambre")]
    public function chambre(): Response
    {
        return $this->render('front/chambre.html.twig');
    }

    #[Route('/restaurant', name: "restaurant")]
    public function restaurant(): Response
    {
        return $this->render('front/restaurant.html.twig');
    }



    #[Route('/spa', name: "spa")]
    public function spa(): Response
    {
        return $this->render('front/spa.html.twig');
    }



    #[Route('/reservation', name: "reservation")]
    public function reservation(): Response
    {
        return $this->render('front/reservation.html.twig');
    }


    #[Route('/lhotel', name: "lhotel")]
    public function lhotel(): Response
    {
        return $this->render('front/lhotel.html.twig');
    }


    #[Route('/actualites', name: "actualites")]
    public function actualites(): Response
    {
        return $this->render('front/actualites.html.twig');
    }


    #[Route('/contact', name: "contact")]
    public function contact(): Response
    {
        return $this->render('front/contact.html.twig');
    }
}
