<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use AppBundle\Helper\Helper;
use AppBundle\Helper\Globals;

/**
 * File
 *
 * @ORM\Table(name="fileUpload")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileUploadRepository")
 * @ORM\HasLifecycleCallbacks
 */
class FileUpload
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @Assert\File(maxSize="6000000")
     * @var type 
     */
    protected $file;
    
    private $tempFile;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="nomFichier", type="string", length=255, nullable=true)
     */
    private $nomFichier;

    /**
     * @var string
     *
     * @ORM\Column(name="fileMime", type="string", length=255, nullable=true)
     */
    private $fileMime;

    /**
     * @var int
     *
     * @ORM\Column(name="fileSize", type="bigint", nullable=true)
     */
    private $fileSize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateAt", type="datetime")
     */
    private $updateAt;
    
    private $helper;
    
    public function __construct(){
        
        $this->helper = new Helper();
    }
    
    public function setFile(File $file = null){
        $this->file = $file;
        if($this->file instanceof UploadedFile){
            if($this->nomFichier !== null){
                $this->tempFile = $this->nomFichier;

                $this->updatedAt = new \DateTime();
                $this->nomFichier = null;
            }
        }
        
        return $this;
    }
    
    public function getFile(){
        
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        
        $this->helper = new Helper();
        
        if(null === $this->file){
            
            return;
        }
        
        $this->nomFichier = $this->helper->parseURL($this->file->getClientOriginalName()) . '_' . uniqid() . '.' . $this->file->getClientOriginalExtension();
        $this->updateAt = new \DateTime();
        $this->fileMime = $this->file->getClientMimeType();
        $this->fileSize = $this->file->getClientSize();
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
          return;
        }
        
        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFile) {
            $oldFile = __DIR__.'/../../../web'.$this->getWebPathTemp();
            
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            __DIR__.'/../../../web'.Globals::getDocumentsUploadDir(), // Le répertoire de destination
            $this->nomFichier   // Le nom du fichier à créer, ici « id.extension »
        );

    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFile = $this->getNomFichier();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists(__DIR__.'/../../../web'.Globals::getDocumentsUploadDir().'/'.$this->tempFile)) {
            // On supprime le fichier
            unlink(__DIR__.'/../../../web'.Globals::getDocumentsUploadDir().'/'.$this->tempFile);
        }
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return File
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
     * Set nomFichier
     *
     * @param string $nomFichier
     * @return File
     */
    public function setNomFichier($nomFichier)
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    /**
     * Get nomFichier
     *
     * @return string 
     */
    public function getNomFichier()
    {
        return $this->nomFichier;
    }

    /**
     * Set fileMime
     *
     * @param string $fileMime
     * @return File
     */
    public function setFileMime($fileMime)
    {
        $this->fileMime = $fileMime;

        return $this;
    }

    /**
     * Get fileMime
     *
     * @return string 
     */
    public function getFileMime()
    {
        return $this->fileMime;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     * @return File
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer 
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return File
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
    
    /**
     * Récupération du chemin vers le fichier pour affichage lors de la modification d'un document
     * 
     * @return type
     */
    public function getWebPath()
    {
        return Globals::getDocumentsUploadDir() . '/' . $this->getNomFichier();
    }
    
    public function getWebPathTemp()
    {
        return Globals::getDocumentsUploadDir() . '/' . $this->tempFile;
    }
}
