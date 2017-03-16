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
        // On récupère tous les commentaire signalés
        $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
        $listeCom = $em->findBy(
        array('signaler' => true));
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
                            'listeArticle' => $listeArticle,
                            'listeCom' => $listeCom));
    
    
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
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            
            $auteur = $request->request->get('auteur');
            $contenu = $request->request->get('contenu');
            
            $commentaire->setAuteur($auteur);
            $commentaire->setContenu($contenu);
            $article->addCommentaire($commentaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            
            return $this->redirectToRoute('blog_core_homepage');
             
            
        }
        
        
    }
    public function comrepAction(Request $request, $idArticle, $idCom, $niveau)
    {
        $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
        $article = $em->find($idArticle);
        // On crée notre objet commentaire
        $commentaire = new Commentaire();
        
        
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            
            $auteur = $request->request->get('auteur');
            $contenu = $request->request->get('contenu');
            $commentaire->setAuteur($auteur);
            $commentaire->setContenu($contenu);
            $commentaire->setArticle($article);
            $commentaire->setNiveau($niveau);
            $commentaire->setIdcomreponse($idCom);
            $article->addCommentaire($commentaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            
            return $this->redirectToRoute('blog_core_homepage');
             
            
        } 
        
        
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
    public function updateAction(Request $request, $id)
    {
        $em = $this
                ->getDoctrine()
                ->getManager();
                
        $article = $em->getRepository('BlogBlogBundle:Article')->find($id);
        
        
        if (null === $article) {
        throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

    

        if ($request->isMethod('POST')){
            $titre = $request->request->get('titre');
            $contenu = $request->request->get('contenu');
            $auteur = $request->request->get('auteur');
            $article->setTitre($titre);
            $article->setContenu($contenu);
            $article->setAuteur($auteur);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "L'article a bien été modifié.");
            
            return $this->redirectToRoute('blog_blog_homepage');
        
        }
    }
    public function signalerAction(Request $request, $id)
    {
        $em = $this
                ->getDoctrine()
                ->getManager();
                
        $com = $em->getRepository('BlogBlogBundle:Commentaire')->find($id);
        
        
        if (null === $com) {
        throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
        }

    

        if ($request->isMethod('POST')){
            
            $com->setSignaler(true);
            $em->flush();
            
            return $this->redirectToRoute('blog_core_homepage');
        
        }
    }
    public function supprimercomAction(Request $request, $id)
    {
        $em = $this
                ->getDoctrine()
                ->getManager();
                
        $com = $em->getRepository('BlogBlogBundle:Commentaire')->find($id);
        
        if (null === $com) {
        throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
        }

    

        if ($request->isMethod('POST')){
            $com->setContenu('Ce commentaire a été supprimé !');
            $com->setEditer(true);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Le commentaire a bien été supprimé.");
            
            return $this->redirectToRoute('blog_blog_homepage');
        
        }
    }
}