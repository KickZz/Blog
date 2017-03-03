<?php

namespace Blog\CoreBundle\Controller;

use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CoreController extends Controller
    
{
    
    public function indexAction(Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BlogBlogBundle:Article');
        
        $listeArticle = $em->findAll();
        
        
        return $this->render('BlogCoreBundle:Core:index.html.twig', array(
                            'listeArticle' => $listeArticle));
        
    }
    
    
}