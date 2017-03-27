<?php

namespace Blog\BlogBundle\Controller;

use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Entity\Commentaire;
use Blog\BlogBundle\Form\ArticleType;
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
        $listeArticle = $em->getAllArticles();
        // On récupère tous les commentaire signalés
        $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
        $listeCom = $em->findBy(
        array('signaler' => true,
              'editer' => false));
        
        // On crée notre objet article
        $article = new Article();
        // On crée le formulaire en se basant sur notre objet ListeCom
        
        // On crée le formulaire en se basant sur notre objet article
        $form = $this->createForm(ArticleType::class, $article);
        
        // Si la requête est en POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            // Ajout d'un message pour confirmer l'ajout de l'Article en BDD
            $request->getSession()->getFlashBag()->add('notice', 'Article ajouté.');
            return $this->redirectToRoute('blog_blog_homepage');
        }
        
        return $this->render('BlogBlogBundle:Blog:admin.html.twig', array(
                            'form' => $form->createView(),
                            'listeArticle' => $listeArticle,
                            'listeCom' => $listeCom));
    
        
    }
    public function comAction(Request $request, $idArticle, $page)
    {
       
        // Si la requête est en ajax
        if ($request->isXmlHttpRequest()) {
            
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            $article = $em->find($idArticle);
            // On crée notre objet commentaire
            $commentaire = new Commentaire();
            
            $auteur = $request->request->get('auteur');
            $contenu = $request->request->get('contenu');
            
            $commentaire->setArticle($article);
            $commentaire->setAuteur($auteur);
            $commentaire->setContenu($contenu);
            $article->addCommentaire($commentaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            
            
            $reponse = $this->render('BlogBlogBundle:Blog:listeCom.html.twig', array('article' => $article,
                                                                                    'page' => $page))->getContent();
            return new Response($reponse);
            
            
              
        }
        
        
    }
    public function comRepAction(Request $request, $idArticle, $idCom, $niveau, $page)
    {
        
        // Si la requête est en ajax
        if ($request->isXmlHttpRequest()) {
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            $article = $em->find($idArticle);
            
            $commentaire = new Commentaire();
            
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
            
            $reponse = $this->render('BlogBlogBundle:Blog:listeCom.html.twig', array('article' => $article,
                                                                                    'page' => $page))->getContent();
            return new Response($reponse);
  
        }
        
        
        
    }
    public function supprimerAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            $article = $em->find($id);
            
            
            if (null === $article) {
                throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
            }
            
            
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            
            $listeArticle = $em->getAllArticles();
            
            

            $reponse = $this->render('BlogBlogBundle:Blog:listeArticle.html.twig', array('listeArticle' => $listeArticle))->getContent();
                                     
            return new Response($reponse);
        }
    }
    public function updateAction(Request $request, $id)
    {
        
        if ($request->isXmlHttpRequest()) {
            
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            $article = $em->find($id);
            
        
            if (null === $article) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
            }
            
            

            
            $titre = $request->request->get('titre');
            $contenu = $request->request->get('contenu');
            $auteur = $request->request->get('auteur');
            $article->setTitre($titre);
            $article->setContenu($contenu);
            $article->setAuteur($auteur);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Article');
            
            $listeArticle = $em->getAllArticles();
            
            
            $reponse = $this->render('BlogBlogBundle:Blog:listeArticle.html.twig', array('listeArticle' => $listeArticle))->getContent();
                                     
            return new Response($reponse);
        
            }
        
        
    
    }
    public function signalerAction(Request $request, $id, $page)
    {
        
        
        if ($request->isXmlHttpRequest()) {
            
            $em = $this
                ->getDoctrine()
                ->getManager();
                
            $com = $em->getRepository('BlogBlogBundle:Commentaire')->find($id);
            $article = $com->getArticle();
            if (null === $com) {
            throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
            }
            $com->setSignaler(true);
            $article = $com->getArticle();
            $em->flush();
            
            $reponse = $this->render('BlogBlogBundle:Blog:listeCom.html.twig', array('article' => $article,
                                                                                    'page' => $page))->getContent();
            return new Response($reponse);
        
        }
    }
    public function supprimerComAction(Request $request, $id)
    {
    
        
        if ($request->isXmlHttpRequest()) {
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
            $com = $em->find($id);
            
            
            if (null === $com) {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
            }
            
            $com->setContenu('Ce commentaire a été supprimé !');
            $com->setEditer(true);
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
            $listeCom = $em->findBy(array(
                'signaler' => true,
                'editer' => false));
            }
            

            $reponse = $this->render('BlogBlogBundle:Blog:listeComSignaler.html.twig', array('listeCom' => $listeCom))->getContent();
                                     
            return new Response($reponse);
        
        }
    public function nePasSupprimerComAction(Request $request, $id)
    {
            
        if ($request->isXmlHttpRequest()) {
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
            $com = $em->find($id);
            $listeCom = $em->findBy(array(
                'signaler' => true,
                'editer' => false));
            
            if (null === $com) {
                throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
            }
            
            
            $com->setSignaler(false);
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $em = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('BlogBlogBundle:Commentaire');
            $listeCom = $em->findBy(array(
                'signaler' => true,
                'editer' => false));
            $reponse = $this->render('BlogBlogBundle:Blog:listeComSignaler.html.twig', array('listeCom' => $listeCom))->getContent();
                                     
            return new Response($reponse);
        
        }
    }
    
    public function dernierAction(Request $request)
    {
    $em = $this->getDoctrine()->getManager();

    $liste = $em->getRepository('BlogBlogBundle:Article')->findBy(
      array(),                 // Pas de critère
      array('datePublier' => 'desc'), // On trie par date décroissante
      3,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );

    return $this->render('BlogBlogBundle:Blog:dernierArticle.html.twig', array(
      'liste' => $liste
    ));
    }
}
