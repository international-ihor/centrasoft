<?php

namespace App\Model;

use Doctrine\ORM\EntityRepository;

class GenreRepository extends EntityRepository
{
	public function all(int $limit = 8, int $offset = 0) {
		$dql = "SELECT genre FROM " . Genre::class . " genre";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($offset)->setMaxResults($limit);
        return $query->getResult();
	}
}