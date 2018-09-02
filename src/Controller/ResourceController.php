<?php

namespace App\Controller;

use App\Entity\MachinismCost;
use App\Entity\Resource;
use App\Form\ResourceType;
use App\Repository\MachinismCostRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resource")
 */
class ResourceController extends AbstractController
{
    /**
     * @Route("/", name="resource_index", methods="GET")
     */
    public function index(ResourceRepository $resourceRepository): Response
    {
        return $this->render('resource/index.html.twig', [
            'resources' => $resourceRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="resource_new", methods="GET|POST")
     */
    public function new(Request $request, MachinismCostRepository $machinismCostRepository): Response
    {
        $resource = new Resource();
        $resource->setOwner($this->getUser());

        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($resource);
            $em->flush();

            return $this->redirectToRoute('resource_index');
        }

        $machinismCostsAutoCompleteData = [];
        foreach ($machinismCostRepository->findAll() as $machinismCost) {
            $machinismCostsAutoCompleteData[] = [
                'name' => $machinismCost->getName(),
                'hourly_cost' => $machinismCost->getHourlyCost(),
            ];
        }

        return $this->render('resource/new.html.twig', [
            'resource' => $resource,
            'form' => $form->createView(),
            'machinismCostsAutoCompleteData' => $machinismCostsAutoCompleteData,
        ]);
    }

    /**
     * @Route("/{id}", name="resource_show", methods="GET")
     */
    public function show(Resource $resource): Response
    {
        return $this->render('resource/show.html.twig', ['resource' => $resource]);
    }

    /**
     * @Route("/{id}/edit", name="resource_edit", methods="GET|POST")
     */
    public function edit(Request $request, Resource $resource): Response
    {
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resource_show', ['id' => $resource->getId()]);
        }

        return $this->render('resource/edit.html.twig', [
            'resource' => $resource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resource_delete", methods="DELETE")
     */
    public function delete(Request $request, Resource $resource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resource->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resource);
            $em->flush();
        }

        return $this->redirectToRoute('resource_index');
    }
}
