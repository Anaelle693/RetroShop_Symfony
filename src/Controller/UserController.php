<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Purchase;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security as CoreSecurity;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($hasher->hashPassword($user, $form['password']->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[
        Route('/show/{id}/admin', name: 'user_show_admin', methods: ['GET', 'POST'])
    ]
    public function show(User $user): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[
        Route('/{id}/edit/admin', name: 'user_edit', methods: ['GET', 'POST']),
        Route('/edit', name: 'client_edit', methods: ['GET', 'POST']),
    ]
    public function edit(Request $request, ?User $user,UserPasswordHasherInterface $hasher): Response
    {

        $notification = null;

        if(is_null($user))
        {
            $user = $this->getUser();
        }else{
            if(!$this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $old_password = $form->get('old_password')->getData();

            if($hasher->isPasswordValid($user,$old_password)){
                $new_password = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user,$new_password);
                $user->setPassword($password);

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("notice" , "OUI!");


            } else {
                $this->addFlash("notice" , "NON");

            }

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,

        ]);
    }


    #[Route('/{id}', name: 'user_delete', methods: ['POST']),]
    public function delete(Request $request, User $user): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $request->getSession()->invalidate();
            $this->container->get('security.token_storage')->setToken(null);

        }

        return $this->redirectToRoute('accueil_index', [], Response::HTTP_SEE_OTHER);
    }
}
