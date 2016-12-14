<?php
// src/AppBundle/Entity/Image
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class File {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="file", cascade={"remove"})
     */
    protected $pictures;

    /**
     * @ORM\Column(name="ext", type="string", length=5)
     */
    private $ext;

    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;

    public function getFile()  {
      return $this->file;
    }

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename;

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    public function setFile(UploadedFile $file) {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->ext) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->ext;

            // On réinitialise les valeurs des attributs ext et alt
            $this->ext = null;
            $this->alt = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
          return;
        }

      // Le nom du fichier est son id, on doit juste stocker également son extension
      // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « ext »
      $this->ext = $this->file->guessExtension();

      // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
      $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
      // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
      if (null === $this->file) {
        return;
      }

      // Si on avait un ancien fichier, on le supprime
      if (null !== $this->tempFilename) {
        $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
        if (file_exists($oldFile)) {
          unlink($oldFile);
        }
      }

      // On déplace le fichier envoyé dans le répertoire de notre choix
  //    dump($this->file);
  //    die();
      $this->file->move(
        $this->getUploadRootDir(), // Le répertoire de destination
        $this->id.'.'.$this->ext   // Le nom du fichier à créer, ici « id.extension »
      );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
      // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
      $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->ext;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
      // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
      if (file_exists($this->tempFilename)) {
        // On supprime le fichier
        unlink($this->tempFilename);
      }
    }

    public function getUploadDir() {
      // On retourne le chemin relatif vers l'image pour un navigateur
      return 'uploads/img';
    }

    protected function getUploadRootDir() {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../web/'.$this->getUploadDir();
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
     * Set ext
     *
     * @param string $ext
     *
     * @return File
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return File
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
    /**
     * Constructor
     */
    public function __construct() {
        $this->pictures = new ArrayCollection();
    }

    /**
     * Add picture
     * @param \AppBundle\Entity\Picture $pictures
     * @return File
     */
    public function addPicture(\AppBundle\Entity\Picture $pictures) {
//        dump($pictures);die();
        
        $this->pictures[] = $pictures;
        return $this;
    }

    /**
     * Remove picture
     * @param \AppBundle\Entity\Picture $pictures
     */
    public function removePicture(\AppBundle\Entity\Picture $pictures) {
        $this->pictures->removeElement($pictures);
    }

    /**
     * Get pictures
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures() {
        return $this->pictures->toArray();
    }

    /**
     * Add file
     *
     * @param \AppBundle\Entity\Picture $file
     *
     * @return File
     */
    public function addFile(\AppBundle\Entity\Picture $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \AppBundle\Entity\Picture $file
     */
    public function removeFile(\AppBundle\Entity\Picture $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
