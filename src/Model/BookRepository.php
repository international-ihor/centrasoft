<?php

namespace App\Model;

use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
	public function all(int $limit = 8, int $offset = 0) {
		$dql = "SELECT book FROM " . Book::class . " book";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult($offset)->setMaxResults($limit);
        return $query->getResult();
	}
}