<?php

namespace App\Controller;
use App\Entity\Wish;

use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\throwException;

//#[Route('/wish',name: 'wish')]
final class WishController extends AbstractController
{
   /* #[Route('/wishes', name: 'app_wish_list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [
            'controller_name' => 'WishController',
        ]);
    }*/

    #[Route('/wishes/{id}', name: 'app_wish_detail', requirements: ['id' => '\d+'])]
    public function detail(Wish $wish): Response
    {
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);
    }



    #[Route('/wishes', name: 'app_wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(
            ['isPublished' => true],
            ['dateCreated' => 'DESC']

        );

        if(!$wishes){
            throw $this->createNotFoundException('rien trouvé, recommence ta lettre');
}

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }



}
