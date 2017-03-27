<?php

namespace Blog\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="Blog\BlogBundle\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\ManyToOne(targetEntity="Blog\BlogBundle\Entity\Article", inversedBy="commentaires")
     */
    private $article;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecom", type="datetime")
     */
    private $datecom;

    /**
     * @var int
     *
     * @ORM\Column(name="niveau", type="integer", nullable=true)
     */
    private $niveau;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="signaler", type="boolean")
     */
    private $signaler;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="editer", type="boolean", nullable=true)
     */
    private $editer;
    
    /**
     * @var int
     *
     * @ORM\Column(name="idcomreponse", type="integer", nullable=true)
     */
    private $idcomreponse;

    /**
     * Constructor
     */
    public function __construct()
    {
    $this->datecom = new \Datetime('now',new \DateTimeZone('Europe/Paris'));
    $this->signaler = false;
    $this->editer = false;
    $this->niveau = 0;
    $this->idcomreponse = 0;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Commentaire
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set datecom
     *
     * @param \DateTime $datecom
     *
     * @return Commentaire
     */
    public function setDatecom($datecom)
    {
        $this->datecom = $datecom;

        return $this;
    }

    /**
     * Get datecom
     *
     * @return \DateTime
     */
    public function getDatecom()
    {
        return $this->datecom;
    }

    /**
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return Commentaire
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set article
     *
     * @param \Blog\BlogBundle\Entity\Article $article
     *
     * @return Commentaire
     */
    public function setArticle(\Blog\BlogBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Blog\BlogBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set signaler
     *
     * @param boolean $signaler
     *
     * @return Commentaire
     */
    public function setSignaler($signaler)
    {
        $this->signaler = $signaler;

        return $this;
    }

    /**
     * Get signaler
     *
     * @return boolean
     */
    public function getSignaler()
    {
        return $this->signaler;
    }
    /**
     * Set editer
     *
     * @param boolean $editer
     *
     * @return Commentaire
     */
    public function setEditer($editer)
    {
        $this->editer = $editer;

        return $this;
    }

    /**
     * Get editer
     *
     * @return boolean
     */
    public function getEditer()
    {
        return $this->editer;
    }

    /**
     * Set idcomreponse
     *
     * @param integer $idcomreponse
     *
     * @return Commentaire
     */
    public function setIdcomreponse($idcomreponse)
    {
        $this->idcomreponse = $idcomreponse;

        return $this;
    }

    /**
     * Get idcomreponse
     *
     * @return integer
     */
    public function getIdcomreponse()
    {
        return $this->idcomreponse;
    }
}
