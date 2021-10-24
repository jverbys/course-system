<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FolderRepository::class)
 */
class Folder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="folder")
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="folders")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="subFolders")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $parentFolder;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="parentFolder")
     */
    private $subFolders;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdFolders")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    public function __construct()
    {
        $this->folders = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->subFolders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setFolder($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getFolder() === $this) {
                $file->setFolder(null);
            }
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getParentFolder(): ?self
    {
        return $this->parentFolder;
    }

    public function setParentFolder(?self $parentFolder): self
    {
        $this->parentFolder = $parentFolder;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubFolders(): Collection
    {
        return $this->subFolders;
    }

    public function addSubFolder(self $subFolder): self
    {
        if (!$this->subFolders->contains($subFolder)) {
            $this->subFolders[] = $subFolder;
            $subFolder->setParentFolder($this);
        }

        return $this;
    }

    public function removeSubFolder(self $subFolder): self
    {
        if ($this->subFolders->removeElement($subFolder)) {
            // set the owning side to null (unless already changed)
            if ($subFolder->getParentFolder() === $this) {
                $subFolder->setParentFolder(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
