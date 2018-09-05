<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActiveZones
 *
 * @ORM\Table(name="active_zones", indexes={@ORM\Index(name="fk_active_zone_model", columns={"modelID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActiveZonesRepository")
 */
class ActiveZones
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
     * @ORM\Column(name="zoneREF", type="string", length=255, nullable=true)
     */
    private $zoneref;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=255, nullable=true)
     */
    private $map;

    /**
     * @var \Models
     *
     * @ORM\ManyToOne(targetEntity="Models")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modelID", referencedColumnName="id")
     * })
     */
    private $modelid;

	public function __toString(){
		return $this->zoneref;
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
     * Set zoneref
     *
     * @param string $zoneref
     *
     * @return ActiveZones
     */
    public function setZoneref($zoneref)
    {
        $this->zoneref = $zoneref;

        return $this;
    }

    /**
     * Get zoneref
     *
     * @return string
     */
    public function getZoneref()
    {
        return $this->zoneref;
    }

    /**
     * Set map
     *
     * @param string $map
     *
     * @return ActiveZones
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return string
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set modelid
     *
     * @param \AppBundle\Entity\Models $modelid
     *
     * @return ActiveZones
     */
    public function setModelid(\AppBundle\Entity\Models $modelid = null)
    {
        $this->modelid = $modelid;

        return $this;
    }

    /**
     * Get modelid
     *
     * @return \AppBundle\Entity\Models
     */
    public function getModelid()
    {
        return $this->modelid;
    }
}
