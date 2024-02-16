<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    /**
     * This controller allow us to edit user's profile
     *
     * @param UserRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @param [type] $id
     * @return Response
     */
    public function edit(
        UserRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        $id
    ): Response {

        $user = $repository->findOneBy(["id" => $id]);

        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('security.login');
        }


        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées !'
                );

                return $this->redirectToRoute('home.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect .'
                );
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /************************************************************************************ */

    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        UserRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        $id
    ): Response {
        $user = $repository->findOneBy(["id" => $id]);

        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('security.login');
        }


        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                $user->setPassword(
                    $hasher->hashpassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a bien été modifié !'
                );

                return $this->redirectToRoute('home.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect .'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
