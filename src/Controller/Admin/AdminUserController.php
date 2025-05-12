<?php

namespace App\Controller\Admin;

use App\Form\system;

use App\Entity\system\User;
use App\Form\system\UserForm;

use App\Repository\system\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
final class AdminUserController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('search', '');

        $queryBuilder = $userRepository->createQueryBuilder('u')
            ->orderBy('u.created_at', 'DESC');

        if ($search) {
            $queryBuilder->andWhere('u.nom LIKE :search OR u.prenom LIKE :search OR u.pseudo LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin_user/index.html.twig', [
            'users' => $pagination,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin_user/edit.html.twig', [
            'editForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index');
    }
}
