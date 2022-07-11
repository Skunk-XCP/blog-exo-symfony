<?php

namespace App\Controller;

use App\Entity\Article;
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
        // Je récupère les setters définis dans le dossier Entity/Article.php
        $title = $request->query->get('title');
        $description = $request->query->get('content');
        $author = $request->query->get('author');

        //Je vérifie si les champs de l'article sont vides, et je crée mon nouvel article
        if (!empty($title) &&
            !empty($description)) {
            $article = new Article();

//            Création du nouvel article (contenu) en utilisant les setters

            $article->setTitle($title);
            $article->setDescription($description);
            $article->setIsPublished(true);
//    $article->setPublishedAt(new \DateTime('NOW'));
            $article->setAuthor($author);

//            Utilisation  de la classe EntityManagerInterface pour enregister
//            le nouvel article dans la bdd
            $entityManager->persist($article);
            $entityManager->flush();

            // J'affiche un message si mon article a été ajouté
            $this->addFlash('success', "L'article a été ajouté");
            return $this->redirectToRoute('admin_article_list');
        }
        // sinon, je mets un message d'erreur
        $this->addFlash('error', 'Merci de remplir le titre et le contenu !');
        return $this->render('Admin/insert_article.html.twig');

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

    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        $article->setTitle("Stardew Valley");

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('OK');
    }
}