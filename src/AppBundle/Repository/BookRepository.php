<?php

namespace AppBundle\Repository;

/**
 * bookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository {
  public function findBooksByUser(int $user) {

    $qb = $this->createQueryBuilder('b');
    $qb
      ->innerJoin('b.user', 'u')
      ->addSelect('u')
      ->where($qb->expr()->in('u.id', $user))
    ;
    return $qb
      ->getQuery()
      ->getResult()
    ;

  }
  
}
