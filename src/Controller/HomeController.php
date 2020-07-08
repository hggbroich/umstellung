<?php

namespace App\Controller;

use DateTime;
use SchoolIT\CommonBundle\Helper\DateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/")
     */
    public function redirectToStart() {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/start", name="index")
     */
    public function index(DateHelper $dateHelper) {
        $date = new DateTime($this->getParameter('START_DATE'));

        if($dateHelper->getToday() >= $date) {
            return $this->render('home/landing.html.twig');
        }

        return $this->render('home/index.html.twig');
    }
}