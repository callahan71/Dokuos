<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Users; //probar

/**
 * VideoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRepository extends \Doctrine\ORM\EntityRepository
{
	public function findUserVideosOrderedByName(Users $user)
    {
        return $this->getEntityManager()
            ->createQuery(
					'SELECT v
					   FROM AppBundle:Videos v
					  WHERE v.userid = :id
				   ORDER BY v.video ASC'
				)->setParameter('id', $user->getId())
            ->getResult();
    }
}
