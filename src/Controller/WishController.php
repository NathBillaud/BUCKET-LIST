<?php

namespace App\Controller;
use App\Entity\Wish;

use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function PHPUnit\Framework\throwException;
use App\Form\WishType;



//#[Route('/wish',name: 'wish')]
#[IsGranted("ROLE_USER")]
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
    #[IsGranted('ROLE_USER')]
    public function detail(Wish $wish): Response
    {
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);
    }



    #[Route('/wishes', name: 'app_wish_list')]
    #[IsGranted('ROLE_USER')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(
            ['isPublished' => true],
            ['dateCreated' => 'DESC']

    );

      //  if(!$wishes){
        //    throw $this->createNotFoundException('rien trouvé, recommence ta lettre');
//}

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/wishes/create', name: 'app_wish_create')]
    #[IsGranted("ROLE_ADMIN")]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'le voeu a été pris en compte!');

            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/edit.html.twig', [
            'wish_form' => $form->createView(),
        ]);
    }

    #[Route('/wishes/update{id}', name: 'app_wish_update', requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_ADMIN")]
    public function update(Request $request, Wish $wish, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Wish successfully updated!');
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/edit.html.twig', [
            'wish_form' => $form,

        ]);


    }


    #[Route('/wishes/{id}/delete', name: 'app_wish_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Wish $wish, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wish->getId(), $request->request->get('_token'))) {
            $em->remove($wish);
            $em->flush();
            $this->addFlash('success', 'Wish successfully deleted!');
        }else{
            $this->addFlash('danger', 'le voeu ne peut etre supprimé');
        }

        return $this->redirectToRoute('app_wish_list');
    }



}
