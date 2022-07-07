<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/insert-category", name="insert_category")
     *
     */

    public function insertCategory(EntityManagerInterface $entityManager)
    {
        $category = new Category();

// gngngnnnnnnnn je fé 1 AVC

        $category->setTitle("Rectification pour le tueur de Cannes");
        $category->setDescription("Le tueur n'utilise pas une saucisse mais bien une faucille, sinon il a toujours son marteau");
        $category->setIsPublished(true);


//            Sauvegarde dans la BDD
        $entityManager->persist($category);
        $entityManager->flush();

        dump($category);
        die;
    }

    /**
     * @Route("/category", name="category")
     *
     */
    public function showArticle(CategoryRepository $categoryRepository)
    {

        $category = $categoryRepository->find(1);

        dd($category);
    }
}