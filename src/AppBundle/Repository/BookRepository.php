<?php

namespace AppBundle\Repository;

/**
 * bookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository {
  public function findBooksByUser($user) {

    $qb = $this->createQueryBuilder('b');
    $qb
      ->innerJoin('b.user', 'u')
      ->addSelect('u')
      ->where($qb->expr()->in('u.id', $user))
      ->orderBy('b.title', 'ASC');
    ;
    return $qb
//      ->getQuery()
//      ->getResult()
    ;

  }
  
  public function findTagsByBook($book) {
      
    $qb = $this->createQueryBuilder('b');
    $qb
      ->innerJoin('b.tags', 't')
      ->Select('t.tagName')
      ->where($qb->expr()->in('b.id', $book))
    ;
    return $qb
      ->getQuery()
      ->getResult()
    ;

  }
}
