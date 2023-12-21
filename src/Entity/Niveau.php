<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'niveau', targetEntity: Profile::class)]
    private Collection $profile;

    #[ORM\OneToMany(mappedBy: 'niveau', targetEntity: Post::class)]
    private Collection $post;

    public function __construct()
    {
        $this->profile = new ArrayCollection();
        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getProfile(): Collection
    {
        return $this->profile;
    }

    public function addProfile(Profile $profile): static
    {
        if (!$this->profile->contains($profile)) {
            $this->profile->add($profile);
            $profile->setNiveau($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): static
    {
        if ($this->profile->removeElement($profile)) {
            // set the owning side to null (unless already changed)
            if ($profile->getNiveau() === $this) {
                $profile->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): static
    {
        if (!$this->post->contains($post)) {
            $this->post->add($post);
            $post->setNiveau($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getNiveau() === $this) {
                $post->setNiveau(null);
            }
        }

        return $this;
    }
}
