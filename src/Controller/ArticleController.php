<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{

    /**
     * @Route("/article/{id}", name="article")
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

        return $this->render("article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("/article-list", name="article_list")
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

        return $this->render("articles.html.twig", ['articles' => $articles]);
    }


    /**
     * @Route("/insert-article", name="insert_article")
     *
     */

    public function insertArticle(EntityManagerInterface $entityManager)
    {
//            Création d'une instance de classe Article afin de pouvoir créer
//            un nouvel article dans ma base de données
        $article = new Article();

//            Création du nouvel article (contenu) en utilisant les setters

        $article->setTitle("Le tueur à la saucisse et au marteau");
        $article->setContent("Le tueur de Cannes a encore frappé");
        $article->setIsPublished(true);
        $article->setAuthor("Michel Niouz");

//            Utilisation  de la classe EntityManagerInterface pour enregister
//            le nouvel article dans la bdd
        $entityManager->persist($article);
        $entityManager->flush();

        dump($article);
        die;
    }

    /**
     * @Route("/articles/delete/{id}", name="delete_article")
     */

    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        if (!is_null($article)) {

            $entityManager->remove($article);
            $entityManager->flush();

            return new Response('Supprimé');
        } else {

            return new Response('Déjà supprimé');
        }
    }

    /**
     * @Route("/articles/update/{id}", name="update_article")
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