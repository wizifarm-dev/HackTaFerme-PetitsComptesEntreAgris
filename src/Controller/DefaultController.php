<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Intl\Intl;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(TeamRepository $teamRepository, OperationRepository $operationRepository)
    {
        return $this->render('default/index.html.twig', [
            'teams' => $teamRepository->getForUser($this->getUser()),
            'operations' => $operationRepository->getLastForUser($this->getUser()),
        ]);
    }
}
