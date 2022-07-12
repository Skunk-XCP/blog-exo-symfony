<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminArticleController extends AbstractController
{

    /**
     * @Route("/admin/article/{id}", name="admin_article")
     */
    public function showArticle(ArticleRepository $articleRepository, $id)
    {
        // récupérer depuis la base de données un article
        // en fonction d'un ID
        // donc SELECT * FROM article where id = xxx

        // la classe Repository me permet de faire des requête SELECT
        // dans la table associée
        // la méthode permet de récupérer un élément par rapport à son id
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("/admin/article-list", name="admin_article_list")
     */
    public function showArticleList(ArticleRepository $articleRepository)
    {
        // récupérer depuis la base de données un article
        // en fonction d'un ID
        // donc SELECT * FROM article where id = xxx

        // la classe Repository me permet de faire des requête SELECT
        // dans la table associée
        // la méthode permet de récupérer un élément par rapport à son id

        $articles = $articleRepository->findAll();

        return $this->render("admin/articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("/admin/insert-article", name="admin_insert_article")
     *
     */

    public function insertArticle(EntityManagerInterface $entityManager, Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        // on donne à la variable qui contient le formulaire
        // une instance de la classe request
        // pour que le formulaire puisse récupérer toutes les données
        // des inputs et faire les setters sur  $article automatiquement
        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valides (valeurs
        // des inputs correspondent à ce qui est attendu en bdd pour la table article)
        if ($form->isSubmitted() && $form->isValid()) {
            // alors on enregistre l'article en bdd
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article enregistré');
        }

        // j'affiche mon twig, en lui passant la variable
        // form, qui contient la vue du formulaire, c'est à dire,
        // le résultat de la méthode createView de la variable $form
        return $this->render("admin/insert_article.html.twig", ['form' => $form->createView()]);

    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_delete_article")
     */

    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        if (!is_null($article)) {

            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', "L'article a été supprimé");
            return $this->redirectToRoute('admin_article_list');
        } else {

            $this->addFlash('error', "Element introuvable");
        }
    }

    /**
     * @Route("/admin/articles/update/{id}", name="admin_update_article")
     */

    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        // on donne à la variable qui contient le formulaire
        // une instance de la classe request
        // pour que le formulaire puisse récupérer toutes les données
        // des inputs et faire les setters sur  $article automatiquement
        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valides (valeurs
        // des inputs correspondent à ce qui est attendu en bdd pour la table article)
        if ($form->isSubmitted() && $form->isValid()) {
            // alors on enregistre l'article en bdd
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article enregistré');
        }

        // j'affiche mon twig, en lui passant la variable
        // form, qui contient la vue du formulaire, c'est à dire,
        // le résultat de la méthode createView de la variable $form
        return $this->render("admin/insert_article.html.twig", ['form' => $form->createView()]);
    }
}