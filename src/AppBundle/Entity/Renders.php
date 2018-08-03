<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Renders
 *
 * @ORM\Table(name="renders", uniqueConstraints={@ORM\UniqueConstraint(name="uc_render", columns={"active_zoneID", "materialID"})}, indexes={@ORM\Index(name="fk_render_material", columns={"materialID"}), @ORM\Index(name="IDX_B6FD1BD45ACF4EB1", columns={"active_zoneID"})})
 * @ORM\Entity
 */
class Renders
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
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var \ActiveZones
     *
     * @ORM\ManyToOne(targetEntity="ActiveZones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="active_zoneID", referencedColumnName="id")
     * })
     */
    private $activeZoneid;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Renders
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
     * Set activeZoneid
     *
     * @param \AppBundle\Entity\ActiveZones $activeZoneid
     *
     * @return Renders
     */
    public function setActiveZoneid(\AppBundle\Entity\ActiveZones $activeZoneid = null)
    {
        $this->activeZoneid = $activeZoneid;

        return $this;
    }

    /**
     * Get activeZoneid
     *
     * @return \AppBundle\Entity\ActiveZones
     */
    public function getActiveZoneid()
    {
        return $this->activeZoneid;
    }

    /**
     * Set materialid
     *
     * @param \AppBundle\Entity\Materials $materialid
     *
     * @return Renders
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
}
