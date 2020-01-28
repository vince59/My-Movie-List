<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MoviesList", mappedBy="users")
     */
    private $lists;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|MoviesList[]
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(MoviesList $list): self
    {
        if (!$this->lists->contains($list)) {
            $this->lists[] = $list;
            $list->setUsers($this);
        }

        return $this;
    }

    public function removeList(MoviesList $list): self
    {
        if ($this->lists->contains($list)) {
            $this->lists->removeElement($list);
            // set the owning side to null (unless already changed)
            if ($list->getUsers() === $this) {
                $list->setUsers(null);
            }
        }

        return $this;
    }

    public function validate(): string
    {
      $err = '';
  
      if (empty($this->name) || strlen($this->name) <= 3) {
        $err = $err . "Invalid 'username' field. Must have more than 3 characters.<br>";
      }
      if (empty($this->email) || preg_match('#^[a-zA-Z0-9]+@[a-zA-Z]{2,}\.[a-z]{2,4}$#', $this->email) != 1) {
        $err = $err . "Invalid 'email' field. Wrong format.<br>";
      }
      if (empty($this->password)) {
        $err = $err . "Invalid 'password' field. Can't be blank.<br>";
      }
  
      if (!empty($err)) {
        throw new \Exception($err);
      }
  
      return $err;
    }
}
