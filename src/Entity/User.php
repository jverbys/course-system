<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="participants")
     */
    private $enrolledCourses;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="owner")
     */
    private $createdCourses;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="moderators")
     */
    private $moderatedCourses;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="owner")
     */
    private $createdFolders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPerson;

    public function __construct()
    {
        $this->createdCourses = new ArrayCollection();
        $this->moderatedCourses = new ArrayCollection();
        $this->createdFolders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getEnrolledCourses(): Collection
    {
        return $this->enrolledCourses;
    }

    public function addEnrolledCourse(Course $course): self
    {
        if (!$this->enrolledCourses->contains($course)) {
            $this->enrolledCourses[] = $course;
            $course->addParticipant($this);
        }

        return $this;
    }

    public function removeEnrolledCourse(Course $course): self
    {
        if ($this->enrolledCourses->removeElement($course)) {
            $course->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCreatedCourses(): Collection
    {
        return $this->createdCourses;
    }

    public function addCreatedCourse(Course $createdCourse): self
    {
        if (!$this->createdCourses->contains($createdCourse)) {
            $this->createdCourses[] = $createdCourse;
            $createdCourse->setOwner($this);
        }

        return $this;
    }

    public function removeCreatedCourse(Course $createdCourse): self
    {
        if ($this->createdCourses->removeElement($createdCourse)) {
            // set the owning side to null (unless already changed)
            if ($createdCourse->getOwner() === $this) {
                $createdCourse->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getModeratedCourses(): Collection
    {
        return $this->moderatedCourses;
    }

    public function addModeratedCourse(Course $moderatedCourse): self
    {
        if (!$this->moderatedCourses->contains($moderatedCourse)) {
            $this->moderatedCourses[] = $moderatedCourse;
            $moderatedCourse->addModerator($this);
        }

        return $this;
    }

    public function removeModeratedCourse(Course $moderatedCourse): self
    {
        if ($this->moderatedCourses->removeElement($moderatedCourse)) {
            $moderatedCourse->removeModerator($this);
        }

        return $this;
    }

    /**
     * @return Collection|Folder[]
     */
    public function getCreatedFolders(): Collection
    {
        return $this->createdFolders;
    }

    public function addCreatedFolder(Folder $createdFolder): self
    {
        if (!$this->createdFolders->contains($createdFolder)) {
            $this->createdFolders[] = $createdFolder;
            $createdFolder->setOwner($this);
        }

        return $this;
    }

    public function removeCreatedFolder(Folder $createdFolder): self
    {
        if ($this->createdFolders->removeElement($createdFolder)) {
            // set the owning side to null (unless already changed)
            if ($createdFolder->getOwner() === $this) {
                $createdFolder->setOwner(null);
            }
        }

        return $this;
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

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(string $contactPerson): self
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }
}
