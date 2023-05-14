<?php

namespace App\Controller;

use App\Entity\Gateau;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    public function index(Gateau $gateau, LikeRepository $repository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if ($gateau->isLikedBy($user)) {

            $like = $repository->findOneBy([
                'author' => $user,
                'gateau' => $gateau
            ]);
            $manager->remove($like);
            $isLiked = false;
        } else {

            $like = new Like();
            $like->setGateau($gateau);
            $like->setAuthor($user);
            $manager->persist($like);
            $isLiked = true;

        }
        $manager->flush();

        $data = [
            'liked' => $isLiked,
            'count' => $repository->count(['gateau' => $gateau])
        ];


        return $this->json($data, 200);
    }
}
