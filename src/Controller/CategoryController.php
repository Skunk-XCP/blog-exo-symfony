<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/category-list", name="category_list")
     *
     */
    public function showArticleList(CategoryRepository $categoryRepository)
    {

        $categories = $categoryRepository->findAll();

        return $this->render("categories_list.html.twig", ['categories' => $categories]);
    }

    /**
     * @Route("/categories/{id}", name="show_category")
     */
    public function showCategory($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render('show_category.html.twig', ['category' => $category]);
    }

    /**
     * @Route("/category/delete/{id}", name="delete_category")
     */

    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        if (!is_null($category)) {

            $entityManager->remove($category);
            $entityManager->flush();

            return new Response('Supprimé');
        } else {

            return new Response('Déjà supprimé');
        }
    }

}