<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/form', name: 'app_form', methods: 'POST')]
    public function form(Request $request): Response
    {

        if ($request->isMethod('POST')) {
            $message = $request->get('message');
            $email = $request->get('email');
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $object = $request->get('object');

            if (empty($message) || empty($email) || empty($object)) {
                $this->addFlash('warning', "Veuillez remplir correctement le formulaire en indiquant un email, un objet et un message.");

                return $this->redirectToRoute('app_home');
            }

            if ($email == $cleanEmail && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/", $email)) {
                $this->addFlash('success', "Votre message a bien été envoyé.");

                return $this->redirectToRoute('app_home');
            }
            $this->addFlash('warning', "Le format d'email renseigné n'est pas correct.");
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
