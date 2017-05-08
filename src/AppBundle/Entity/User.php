<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="validators.language.mail")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="nom", type="string", nullable=true)
     */
    private $nom;
    
    /**
     * @ORM\Column(name="prenom", type="string", nullable=true)
     */
    private $prenom;
    
    /**
     * @ORM\Column(name="societe", type="string", nullable=true)
     * 
     * @return string
     */
    private $societe;
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\FileUpload", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $logo;
    
    
    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    

    /**
     * Set societe
     *
     * @param \FloriotBundle\Entity\Societe $societe
     * @return User
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return \FloriotBundle\Entity\Societe 
     */
    public function getSociete()
    {
        return $this->societe;
    }


    /**
     * Set logo
     *
     * @param \AppBundle\Entity\FileUpload $logo
     * @return User
     */
    public function setLogo(\AppBundle\Entity\FileUpload $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \AppBundle\Entity\FileUpload 
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
