<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ActiveZones;

/**
 * RenderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RenderRepository extends \Doctrine\ORM\EntityRepository
{
	
	public function findRendersOrderedByMaterial(ActiveZones $zone){
		return $this->getEntityManager()
            ->createQuery(
					'SELECT r
					   FROM AppBundle:Renders r
					  WHERE r.activeZoneid = :id
				   ORDER BY r.materialid ASC'
				)->setParameter('id', $zone->getId())
            ->getResult();
	}
}
