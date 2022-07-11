<?php

namespace App\Controller;



use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{

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

            $this->addFlash('error',"Element introuvable");
        }
    }

    /**
     * @Route("/admin/insert-category", name="admin_insert_category")
     *
     */

    public function insertCategory(EntityManagerInterface $entityManager, Request $request)
    {
        // Je récupère les setters définis dans le dossier Entity/Catégorie.php
        $title = $request->query->get('title');
        $description = $request->query->get('content');

        //Je vérifie si les champs de la catégorie sont vides, et je crée ma nouvelle catégorie
        if (!empty($title) &&
            !empty($description)){
            $category = new Category();

//            Création de la nouvelle categorie (contenu) en utilisant les setters

            $category->setTitle($title);
            $category->setDescription($description);
            $category->setIsPublished(true);
//            $category->setPublishedAt(new \DateTime('NOW'));

//            Utilisation  de la classe EntityManagerInterface pour enregister
//            la nouvelle catégorie dans la bdd
            $entityManager->persist($category);
            $entityManager->flush();

            // J'affiche un message si ma catégorie a été ajouté
            $this->addFlash('success', "La catégorie a été ajoutée");
            return $this->redirectToRoute('admin_category_list');
        }

        // sinon j'affiche un mesage d'erreur
        $this->addFlash('error', 'Merci de remplir le titre et le contenu !');
        return $this->render('Admin/insert_category.html.twig');
    }


}