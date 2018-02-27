<?php

namespace HV\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumTopicView
 *
 * @ORM\Table(name="p5_forum_topic_view")
 * @ORM\Entity(repositoryClass="HV\ForumBundle\Repository\ForumTopicViewRepository")
 */
class ForumTopicView
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
    * @ORM\ManyToOne(targetEntity="HV\UsersBundle\Entity\Users")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $users;


    /**
    * @ORM\ManyToOne(targetEntity="HV\ForumBundle\Entity\ForumTopic")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $forumTopic;


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
     * Set users
     *
     * @param \HV\UsersBundle\Entity\Users $users
     *
     * @return ForumTopicView
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
     * @return ForumTopicView
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
