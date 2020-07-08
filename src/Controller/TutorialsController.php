<?php

namespace App\Controller;

use App\Registration\CodeManager;
use App\Security\AuthenticationException;
use App\Security\ParentAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tutorials")
 */
class TutorialsController extends AbstractController {

    /**
     * @Route("/parents/new", name="tutorial_new_parents")
     */
    public function newParents() {
        return $this->render('tutorials/parents/new.html.twig');
    }

    /**
     * @Route("/parents/existing", name="tutorial_existing_parents")
     */
    public function existingParents(Request $request, ParentAuthenticator $authenticator, CodeManager $codeManager) {
        $code = null;
        $error = null;
        $user = null;

        if($request->isMethod('POST')) {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            if(empty($username)) {
                $error = 'Bitte einen Benutzernamen eintragen (der Benutzername beginnt mit e.).';
            } else if(empty($password)) {
                $error = 'Bitte das Kennwort eingeben.';
            } else {
                try {
                    $user = $authenticator->authenticate($username, $password);
                    $code = $codeManager->getCode($user->getUsername());
                } catch (AuthenticationException $e) {
                    $error = 'Benutzername und/oder Kennwort sind falsch.';
                }
            }
        }

        return $this->render('tutorials/parents/existing.html.twig', [
            'code' => $code,
            'user' => $user,
            'error' => $error
        ]);
    }

    /**
     * @Route("/link", name="link_students")
     */
    public function linkStudents() {
        return $this->render('tutorials/link.html.twig');
    }

    /**
     * @Route("/students/new", name="tutorial_new_students")
     */
    public function newStudents() {
        return $this->render('tutorials/students/new.html.twig');
    }

    /**
     * @Route("/students/existing", name="tutorial_existing_students")
     */
    public function existingStudents() {
        return $this->render('tutorials/students/existing.html.twig');
    }
}