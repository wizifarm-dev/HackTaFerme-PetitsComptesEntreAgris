<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamInviteType;
use App\Form\TeamType;
use App\Repository\OperationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/new", name="team_new", methods="GET|POST")
     */
    public function new(Request $request) : Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->addTeam($team);

            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="team_show", methods="GET")
     */
    public function show(Team $team) : Response
    {
        return $this->render('team/show.html.twig', ['team' => $team]);
    }

    /**
     * @Route("/{id}/edit", name="team_edit", methods="GET|POST")
     */
    public function edit(Request $request, Team $team) : Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('team_edit', ['id' => $team->getId()]);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/operations", name="team_operations_index", methods="GET")
     */
    public function index(Team $team, OperationRepository $operationRepository) : Response
    {
        return $this->render('team/operations.html.twig', [
            'team' => $team,
            'operations' => $operationRepository->findByTeam($team)
        ]);
    }

    /**
     * @Route("/{id}/invite", name="team_invite", methods="GET|POST")
     */
    public function invite(Request $request, Team $team, UserRepository $userRepository) : Response
    {
        $form = $this->createForm(TeamInviteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            if ($user = $userRepository->findOneByEmail($email)) {
                $team->addUser($user);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
            }

            $form->get('email')->addError(new FormError(sprintf(
                'Aucun utilisateur avec l\'email %s',
                $email
            )));
        }

        return $this->render('team/invite.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="team_delete", methods="DELETE")
     */
    public function delete(Request $request, Team $team) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
        }

        return $this->redirectToRoute('dashboard');
    }
}
