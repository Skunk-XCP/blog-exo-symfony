<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdminController extends AbstractController
{
    /**
     * @Route("/admin/admins", name="admin_list_admins")
     */
    public function listAdmins(UserRepository $userRepository)
    {
        $admins = $userRepository->findAll();

        return$this->render('Admin/admins.html.twig', ['admins' => $admins]);
    }

    /**
     * @Route("/admin/create", name="admin_create_admins")
     */
    public function createAdmin(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        $user->setRoles(["ROLE_ADMIN"]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            $user->getPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Admin créé');

            return $this->redirectToRoute('admin_list_admins');
        }

        return $this->render('Admin/insert_admin.html.twig', ['form' => $form->createView()]);
    }
}