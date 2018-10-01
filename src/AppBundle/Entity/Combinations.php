<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Combinations
 *
 * @ORM\Table(name="combinations", uniqueConstraints={@ORM\UniqueConstraint(name="uc_combination", columns={"keyCHAR", "showcaseID"})}, indexes={@ORM\Index(name="fk_combination_showcase", columns={"showcaseID"}), @ORM\Index(name="fk_combination_material", columns={"materialID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CombinationRepository")
 */
class Combinations
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
     * @ORM\Column(name="keyCHAR", type="string", length=2, nullable=false)
     */
    private $keychar;

    /**
     * @var \Materials
     *
     * @ORM\ManyToOne(targetEntity="Materials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materialID", referencedColumnName="id")
     * })
     */
    private $materialid;

    /**
     * @var \Showcases
     *
     * @ORM\ManyToOne(targetEntity="Showcases")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="showcaseID", referencedColumnName="id")
     * })
     */
    private $showcaseid;



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
     * Set keychar
     *
     * @param string $keychar
     *
     * @return Combinations
     */
    public function setKeychar($keychar)
    {
        $this->keychar = $keychar;

        return $this;
    }

    /**
     * Get keychar
     *
     * @return string
     */
    public function getKeychar()
    {
        return $this->keychar;
    }

    /**
     * Set materialid
     *
     * @param \AppBundle\Entity\Materials $materialid
     *
     * @return Combinations
     */
    public function setMaterialid(\AppBundle\Entity\Materials $materialid = null)
    {
        $this->materialid = $materialid;

        return $this;
    }

    /**
     * Get materialid
     *
     * @return \AppBundle\Entity\Materials
     */
    public function getMaterialid()
    {
        return $this->materialid;
    }

    /**
     * Set showcaseid
     *
     * @param \AppBundle\Entity\Showcases $showcaseid
     *
     * @return Combinations
     */
    public function setShowcaseid(\AppBundle\Entity\Showcases $showcaseid = null)
    {
        $this->showcaseid = $showcaseid;

        return $this;
    }

    /**
     * Get showcaseid
     *
     * @return \AppBundle\Entity\Showcases
     */
    public function getShowcaseid()
    {
        return $this->showcaseid;
    }
}
