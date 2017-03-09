<?php

namespace Blog\BlogBundle\Controller;

use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Entity\Commentaire;
use Blog\BlogBundle\Form\ArticleType;
use Blog\BlogBundle\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BlogController extends Controller
    
{
    
    public function adminAction(Request $request)
    {
        // On récupère tout nos articles
        $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
        $listeArticle = $em->findAll();
        // On crée notre objet article
        $article = new Article();
        
        // On crée le formulaire en se basant sur notre objet article
        $form = $this->createForm(ArticleType::class, $article);
        
        // Si la requête est en POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            // Ajout d'un message pour confirmer l'ajout de l'Article en BDD
            $request->getSession()->getFlashBag()->add('notice', 'Article ajoutée.');
        }
        return $this->render('BlogBlogBundle:Blog:admin.html.twig', array(
                            'form' => $form->createView(),
                            'listeArticle' => $listeArticle));
    
    
    }
    public function comAction(Request $request, $idArticle)
    {
        $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
        $article = $em->find($idArticle);
        // On crée notre objet commentaire
        $commentaire = new Commentaire();
        $commentaire->setArticle($article);
        
        // On crée le formulaire en se basant sur notre objet commentaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        // Si la requête est en POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $article->addCommentaire($commentaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            
            return $this->redirectToRoute('blog_core_homepage');
             
            
        }
        return $this->render('BlogBlogBundle:Blog:formcommentaire.html.twig', array(
                             'form' => $form->createView(),
                             'idArticle' => $idArticle));
        
    }
    public function supprimerAction(Request $request, $id)
    {
        $em = $this
                ->getDoctrine()
                ->getManager();
                
        $article = $em->getRepository('BlogBlogBundle:Article')->find($id);
        
        if (null === $article) {
        throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

    

        if ($request->isMethod('POST')){
            $em->remove($article);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "L'article a bien été supprimé.");
            
            return $this->redirectToRoute('blog_blog_homepage');
        
        }
    }
}