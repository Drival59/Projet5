<?php

namespace HV\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumTopic
 *
 * @ORM\Table(name="forum_topic")
 * @ORM\Entity(repositoryClass="HV\ForumBundle\Repository\ForumTopicRepository")
 */
class ForumTopic
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
     * @ORM\ManyToOne(targetEntity="HV\ForumBundle\Entity\ForumSection")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forumSection;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
    * @ORM\ManyToOne(targetEntity="HV\UsersBundle\Entity\Users")
    * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="view", type="integer")
     */
    private $view;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_post", type="datetime")
     */
    private $dateLastPost;

    public function __construct()
    {
      $this->setDateLastPost();
      $this->setView(0);
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
     * Set subject
     *
     * @param string $subject
     *
     * @return ForumTopic
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * Set forumSection
     *
     * @param \HV\ForumBundle\Entity\ForumSection $forumSection
     *
     * @return ForumTopic
     */
    public function setForumSection(\HV\ForumBundle\Entity\ForumSection $forumSection)
    {
        $this->forumSection = $forumSection;

        return $this;
    }

    /**
     * Get forumSection
     *
     * @return \HV\ForumBundle\Entity\ForumSection
     */
    public function getForumSection()
    {
        return $this->forumSection;
    }

    /**
     * Set users
     *
     * @param \HV\UsersBundle\Entity\Users $users
     *
     * @return ForumTopic
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
     * Set view
     *
     * @param integer $view
     *
     * @return ForumTopic
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return integer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set dateLastPost
     *
     * @param \DateTime $dateLastPost
     *
     * @return ForumTopic
     */
    public function setDateLastPost()
    {
        $this->dateLastPost = new \Datetime();
        return $this;
    }

    /**
     * Get dateLastPost
     *
     * @return \DateTime
     */
    public function getDateLastPost()
    {
        return $this->dateLastPost;
    }
}
