<?php

namespace Blog\BlogBundle\Controller;

use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Entity\Commentaire;
use Blog\BlogBundle\Form\ArticleType;
use Blog\BlogBundle\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
    
{
    
    public function adminAction(Request $request)
    {
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
                            ));
    
    
    }
}