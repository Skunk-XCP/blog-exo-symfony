<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CustomerArticleController extends AbstractController
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

        return $this->render("customer_article.html.twig", ['article' => $article]);
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

        return $this->render("customer_articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("/articles/search", name="search_articles")
     */
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        // Je récupère une valeur dans ma recherche
        $search = $request->query->get('search');

        // Je crée une méthode qui recherche une correspondance entre la valeur récupérée
        // et un mot contenu dans le titre ou le contenu de l'article
        $articles = $articleRepository->searchByWord($search);

        // On retourne une réponse twig avec les articles trouvés et on les affiche
        return $this->render('customer_search_articles.html.twig', ['articles' => $articles]);
    }
}