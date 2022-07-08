<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/insert-category", name="admin_insert_category")
     *
     */

    public function insertCategory(EntityManagerInterface $entityManager)
    {
        $category = new Category();


        $category->setTitle("Rectification pour le tueur de Cannes");
        $category->setDescription("Le tueur n'utilise pas une saucisse mais bien une faucille, sinon il a toujours son marteau");
        $category->setIsPublished(true);

        $this->addFlash('success','La catégorie a été ajoutée');


//            Sauvegarde dans la BDD
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('admin_category_list');
    }

//    /**
//     * @Route("/admin/category", name="admin_category")
//     *
//     */
//    public function showCategory(CategoryRepository $categoryRepository)
//    {
//
//        $category = $categoryRepository->find(1);
//
//        dd($category);
//    }

    /**
     * @Route("/admin/category-list", name="admin_category_list")
     *
     */
    public function showCategoryList(CategoryRepository $categoryRepository)
    {

        $categories = $categoryRepository->findAll();

        return $this->render("Admin/categories_list.html.twig", ['categories' => $categories]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin_show_category")
     */
    public function showCategorySingle($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        return $this->render('Admin/show_category.html.twig', ['category' => $category]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_delete_category")
     */

    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        if (!is_null($category)) {

            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success','La catégorie a été supprimée');
            return $this->redirectToRoute('admin_category_list');
        } else {

            return new Response('Déjà supprimé');
        }
    }

}