<?php

namespace App\Controller;

use App\Entity\Gateau;
use App\Entity\Ingredient;
use App\Form\GateauType;
use App\Repository\GateauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GateauController extends AbstractController
{
    #[Route('/gateau', name: 'app_gateau')]
    public function index(GateauRepository $gateauRepository): Response
    {
        return $this->render('gateau/index.html.twig', [
            'gateaux' => $gateauRepository->findAll(),
        ]);
    }

    #[Route('/gateau/create', name: 'app_gateau_create')]
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $gateau = new Gateau();
        $formGateau = $this->createForm(GateauType::class, $gateau);
        $formGateau->handleRequest($request);
        if ($formGateau->isSubmitted() && $formGateau->isValid())
        {
            $ingredients = $formGateau->getData()->getIngredients();
            foreach($ingredients as $ingredient){
                $newIngredient = new Ingredient();
                $newIngredient->setNom($ingredient->getNom());
                $newIngredient->setGateau($gateau);
            }
            $images = $formGateau->getData()->getImages();
            foreach($images as $image){
                $image->setGateau($gateau);
            }
            $manager->persist($gateau);
            $manager->flush();
            return $this->redirectToRoute('app_gateau_show', ['id'=>$gateau->getId()]);
        }

        return $this->renderForm('gateau/create.html.twig', [
            'formGateau' => $formGateau,
        ]);
    }

    #[Route('/gateau/{id}', name: 'app_gateau_show')]
    public function show(Gateau $gateau): Response
    {
        return $this->render('gateau/show.html.twig', [
            'gateau' => $gateau,
        ]);
    }

    #[Route('/gateau/delete/{id}', name: 'app_gateau_delete')]
    public function delete(Gateau $gateau, EntityManagerInterface $manager): Response
    {
        $manager->remove($gateau);
        $manager->flush();
        return $this->redirectToRoute('app_gateau');
    }
}
