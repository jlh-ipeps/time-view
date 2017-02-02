<?php

namespace AppBundle\Repository;

/**
 * PictureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PictureRepository extends \Doctrine\ORM\EntityRepository {

    public function findPicturesByBook(int $id, $maxThumbNbr) {
        $qb = $this->createQueryBuilder('p');
        $qb
          ->Join('p.file', 'f')
          ->Join('p.book', 'b')
          ->Select('p')
          ->AddSelect('p.title')
          ->AddSelect('p.info')
          ->AddSelect('p.lat')
          ->AddSelect('p.lng')
          ->AddSelect('(f.id) AS file_id')
          ->AddSelect('(f.ext) AS file_ext')
          ->AddSelect('f.width / f.height AS file_ratio')
          ->AddSelect('(b.id) AS book_id')
          ->where($qb->expr()->in('p.book', $id))
          ->orderBy('f.id', 'DESC')
        ;
        return $qb
          ->getQuery()
          ->getResult()
        ;
    }
    
    public function findNewImages() {
        $limit = 100;
        $qb = $this->createQueryBuilder('p');
        $qb
          ->innerJoin('p.file', 'f')
          ->Select('p')
          ->orderBy('f.id', 'ASC')
          ->setMaxResults( $limit );
        ;
        return $qb
          ->getQuery()
          ->getResult()
        ;
  }

    public function findRandomImages() {
        $limit = 100;
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('p.file', 'f')
            ->Select('p')
            ->orderBy('RAND()')
            ->setMaxResults( $limit );
        ;
        return $qb
            ->getQuery()
            ->getResult()
        ;
  }

    public function findPopularImages($maxThumbNbr) {
        $limit = $maxThumbNbr;
        $qb = $this->createQueryBuilder('p');
        $qb
          ->Join('p.file', 'f')
          ->Join('p.book', 'b')
          ->Select('p')
          ->AddSelect('p.title')
          ->AddSelect('p.info')
          ->AddSelect('p.lat')
          ->AddSelect('p.lng')
          ->AddSelect('(f.id) AS file_id')
          ->AddSelect('(f.ext) AS file_ext')
          ->AddSelect('f.width / f.height AS file_ratio')
          ->AddSelect('(b.id) AS book_id')
          ->orderBy('f.id', 'DESC')
          ->setMaxResults( $limit );
        ;
        return $qb
          ->getQuery()
          ->getResult()
        ;
  }
  

}
