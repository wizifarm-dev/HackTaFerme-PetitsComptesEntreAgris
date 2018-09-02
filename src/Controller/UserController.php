<?php

namespace App\Controller;

use App\Form\MyAccountType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/my-account", name="my-account")
     */
    public function myAccount(Request $request, ObjectManager $entityManager) : Response
    {
        $user = $this->getUser();

        $form = $this->createForm(MyAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user/my_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}