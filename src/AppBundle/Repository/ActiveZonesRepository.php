<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Models;

/**
 * ActiveZonesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActiveZonesRepository extends \Doctrine\ORM\EntityRepository
{
	
	public function findModelZonesOrderedByRef(Models $model){
		return $this->getEntityManager()
            ->createQuery(
					'SELECT z
					   FROM AppBundle:ActiveZones z
					  WHERE z.modelid = :id
				   ORDER BY z.zoneref ASC'
				)->setParameter('id', $model->getId())
            ->getResult();
	}
}
