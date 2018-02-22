<?php

namespace HV\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Comments
 *
 * @ORM\Table(name="p5_comments")
 * @ORM\Entity(repositoryClass="HV\NewsBundle\Repository\CommentsRepository")
 */
class Comments
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
     * @ORM\ManyToOne(targetEntity="HV\NewsBundle\Entity\News")
     * @ORM\JoinColumn(nullable=false)
     */
    private $news;

    /**
     * @ORM\ManyToOne(targetEntity="HV\UsersBundle\Entity\Users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     * @Assert\Length(max=255, maxMessage="Le commentaire ne doit pas faire plus de {{ limit }} caractères.")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comment", type="datetime")
     */
    private $dateComment;

    public function __construct()
    {
      $this->setDateComment();
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
     * Set content
     *
     * @param string $content
     *
     * @return Comments
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
     * Set dateComment
     *
     * @param \DateTime $dateComment
     *
     * @return Comments
     */
    public function setDateComment()
    {
        $this->dateComment = new \Datetime();
        return $this;
    }

    /**
     * Get dateComment
     *
     * @return \DateTime
     */
    public function getDateComment()
    {
        return $this->dateComment;
    }

    /**
     * Set news
     *
     * @param \HV\NewsBundle\Entity\News $news
     *
     * @return Comments
     */
    public function setNews(\HV\NewsBundle\Entity\News $news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return \HV\NewsBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set users
     *
     * @param \HV\UsersBundle\Entity\Users $users
     *
     * @return Comments
     */
    public function setUsers(\HV\UsersBundle\Entity\Users $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \HV\UsersBundle\Entity\Users
     */
    public function getUsers()
    {
        return $this->users;
    }
}
