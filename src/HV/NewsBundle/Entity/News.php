<?php

namespace HV\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="HV\NewsBundle\Repository\NewsRepository")
 */
class News
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_news", type="datetime")
     */
    private $dateNews;

    /**
     * @ORM\Column(name="in_carousel", type="boolean")
     */
    private $inCarousel = false;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255)
     */
    private $imageUrl;

    public function __construct()
    {
      // Par défaut, la date de l'annonce est la date d'aujourd'hui
      $this->dateNews = new \Datetime();
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
     * Set title
     *
     * @param string $title
     *
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateNews
     *
     * @param \DateTime $dateNews
     *
     * @return News
     */
    public function setDateNews($dateNews)
    {
        $this->dateNews = $dateNews;

        return $this;
    }

    /**
     * Get dateNews
     *
     * @return \DateTime
     */
    public function getDateNews()
    {
        return $this->dateNews;
    }

    /**
     * Set inCarousel
     *
     * @param boolean $inCarousel
     *
     * @return News
     */
    public function setInCarousel($inCarousel)
    {
        $this->inCarousel = $inCarousel;

        return $this;
    }

    /**
     * Get inCarousel
     *
     * @return boolean
     */
    public function getInCarousel()
    {
        return $this->inCarousel;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return News
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
