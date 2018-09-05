<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Showcases;

/**
 * CombinationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CombinationRepository extends \Doctrine\ORM\EntityRepository
{
	public function findShowcaseCombinationsOrderedByKey(Showcases $showcase){
		return $this->getEntityManager()
            ->createQuery(
					'SELECT c
					   FROM AppBundle:Combinations c
					  WHERE c.showcaseid = :id
				   ORDER BY c.keychar ASC'
				)->setParameter('id', $showcase->getId())
            ->getResult();
	}
}