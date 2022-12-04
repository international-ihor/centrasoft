<?php

namespace App\Model;

use Doctrine\ORM\EntityRepository;

class RecordRepository extends EntityRepository
{
	public function all(int $limit = 8, int $offset = 0) {
		$dql = "SELECT record FROM " . Record::class . " record";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($offset)->setMaxResults($limit);
        return $query->getResult();
	}
}