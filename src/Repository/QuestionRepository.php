<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function add(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Question[] returns an array of Question objects
     */
    
    public function createAskedOrderedByNewestQueryBuilder() : QueryBuilder
    {
            return $this->addIsAskedQueryBuilder()
                ->orderBy('q.askedAt', 'DESC')
                ->leftJoin('q.questionTags', 'question_tag')
                ->innerJoin('question_tag.tag', 'tag')
                ->addSelect(['question_tag', 'tag']);
    }
    
    private function addIsAskedQueryBuilder(QueryBuilder $queryBuilder = null) : QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)
            ->andWhere('q.askedAt IS NOT NULL');
    }
    
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null) : QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('q');
    }

//    public function findOneBySomeField($value): ?Question
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
