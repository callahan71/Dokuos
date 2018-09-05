<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="models", uniqueConstraints={@ORM\UniqueConstraint(name="uc_model", columns={"ref"})}, indexes={@ORM\Index(name="fk_model_user", columns={"userID"}), @ORM\Index(name="fk_model_category", columns={"categoryID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelRepository")
 */
class Models
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=255, nullable=false)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
	 * @var \Categories
     *
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="models")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoryID", referencedColumnName="id")
     * })
     */
    private $categoryid;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userID", referencedColumnName="id")
     * })
     */
    private $userid;

	public function __toString(){
		return $this->name;
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Models
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Models
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Models
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
     * Set categoryid
     *
     * @param \AppBundle\Entity\Categories $categoryid
     *
     * @return Models
     */
    public function setCategoryid(\AppBundle\Entity\Categories $categoryid = null)
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    /**
     * Get categoryid
     *
     * @return \AppBundle\Entity\Categories
     */
    public function getCategoryid()
    {
        return $this->categoryid;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\Users $userid
     *
     * @return Models
     */
    public function setUserid(\AppBundle\Entity\Users $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
