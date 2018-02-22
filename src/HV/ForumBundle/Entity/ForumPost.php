<?php

namespace HV\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ForumPost
 *
 * @ORM\Table(name="forum_post")
 * @ORM\Entity(repositoryClass="HV\ForumBundle\Repository\ForumPostRepository")
 */
class ForumPost
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_post", type="datetime")
     */
    private $datePost;

    /**
    * @ORM\ManyToOne(targetEntity="HV\UsersBundle\Entity\Users")
    * @ORM\JoinColumn(nullable=false)
    */
    private $users;

    /**
    * @ORM\ManyToOne(targetEntity="HV\ForumBundle\Entity\ForumTopic")
    * @ORM\JoinColumn(nullable=false)
    */
    private $forumTopic;


    public function __construct()
    {
      $this->setDatePost();
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
     * @return ForumPost
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
     * Set datePost
     *
     * @param \DateTime $datePost
     *
     * @return ForumPost
     */
    public function setDatePost()
    {
        $this->datePost = new \Datetime();
        return $this;
    }

    /**
     * Get datePost
     *
     * @return \DateTime
     */
    public function getDatePost()
    {
        return $this->datePost;
    }

    /**
     * Set users
     *
     * @param \HV\UsersBundle\Entity\Users $users
     *
     * @return ForumPost
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

    /**
     * Set forumTopic
     *
     * @param \HV\ForumBundle\Entity\ForumTopic $forumTopic
     *
     * @return ForumPost
     */
    public function setForumTopic(\HV\ForumBundle\Entity\ForumTopic $forumTopic)
    {
        $this->forumTopic = $forumTopic;

        return $this;
    }

    /**
     * Get forumTopic
     *
     * @return \HV\ForumBundle\Entity\ForumTopic
     */
    public function getForumTopic()
    {
        return $this->forumTopic;
    }
}
