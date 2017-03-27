<?php

namespace Blog\CoreBundle\Controller;

use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Entity\Commentaire;
use Blog\BlogBundle\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CoreController extends Controller
    
{
    
    public function indexAction(Request $request, $page)
    {
        if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        $nbParPage = 3;
        
        // On récupère notre objet Paginator
        $listeArticle = $this->getDoctrine()
        ->getManager()
        ->getRepository('BlogBlogBundle:Article')
        ->getArticles($page, $nbParPage)
        ;
        
        
        // On calcule le nombre total de pages grâce au count
        $nbPages = ceil(count($listeArticle) / $nbParPage);
        
        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
        throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        
        return $this->render('BlogCoreBundle:Core:index.html.twig', array(
                            'listeArticle' => $listeArticle,
                            'nbPages'     => $nbPages,
                            'page'        => $page));
        
    }
    
    
    
}