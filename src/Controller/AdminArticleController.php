<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


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

    public function insertArticle(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger)
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

            $image = $form->get('image')->getData();

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $slugger->slug($originalFilename);

            $newFilename = $safeFilename."-".uniqid().'.'.$image->guessExtension();

            $image->move($this->getParameter('images_directory'), $newFilename);

            $article->setImage($newFilename);

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

    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger)
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
            // Je récupère l'image dans le formulaire
            $image = $form->get('image')->getData();
            //Je récupère le nom du fichier original
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // L'instance de classe Slugger permet de suprrimer les caractères spéciaux, espaces du nom du fichier
            $safeFilename = $slugger->slug($originalFilename);
            // On ajoute un identifiant au nom de l'image au cas ou elle serait postée plusieurs fois
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
            // je déplace l'image dans le dossier public et je la renomme avec le nouveau nom créé
            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $article->setImage($newFilename);


            // alors on enregistre l'article en bdd
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article enregistré');
        }

        // j'affiche mon twig, en lui passant la variable
        // form, qui contient la vue du formulaire, c'est à dire,
        // le résultat de la méthode createView de la variable $form
        return $this->render("admin/update_article.html.twig", ['form' => $form->createView(), 'article' => $article]);
    }

    /**
     * @Route("/admin/articles/search", name="admin_search_articles")
     */
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        // Je récupère une valeur dans ma recherche
        $search = $request->query->get('search');

        // Je crée une méthode qui recherche une correspondance entre la valeur récupérée
        // et un mot contenu dans le titre ou le contenu de l'article
        $articles = $articleRepository->searchByWord($search);

        // On retourne une réponse twig avec les articles trouvés et on les affiche
        return $this->render('admin/search_articles.html.twig', ['articles' => $articles]);
    }
}