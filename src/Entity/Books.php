<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BooksRepository::class)
 */
class Books
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Title field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "The title is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="ISBN field can not be empty!")
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "The ISBN is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The ISBN cannot be longer than {{ limit }} characters"
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Pages field can not be empty!")
     * @Assert\Positive(message="Pages field cannot have zero or negative amount of pages")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $pages;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description field can not be empty!")
     */
    private $about;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $author_id;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
