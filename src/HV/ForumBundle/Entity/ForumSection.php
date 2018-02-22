<?php

namespace HV\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumSection
 *
 * @ORM\Table(name="p5_forum_section")
 * @ORM\Entity(repositoryClass="HV\ForumBundle\Repository\ForumSectionRepository")
 */
class ForumSection
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
     * @ORM\ManyToOne(targetEntity="HV\ForumBundle\Entity\ForumCategory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forumCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;


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
     * Set name
     *
     * @param string $name
     *
     * @return ForumSection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set forumCategory
     *
     * @param \HV\ForumBundle\Entity\ForumCategory $forumCategory
     *
     * @return ForumSection
     */
    public function setForumCategory(\HV\ForumBundle\Entity\ForumCategory $forumCategory)
    {
        $this->forumCategory = $forumCategory;

        return $this;
    }

    /**
     * Get forumCategory
     *
     * @return \HV\ForumBundle\Entity\ForumCategory
     */
    public function getForumCategory()
    {
        return $this->forumCategory;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ForumSection
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
