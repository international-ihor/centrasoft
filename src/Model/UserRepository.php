<?php

namespace App\Model;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	public function all(int $limit = 8, int $offset = 0) {
		$dql = "SELECT user FROM " . User::class . " user";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($offset)->setMaxResults($limit);
        return $query->getResult();
	}
}