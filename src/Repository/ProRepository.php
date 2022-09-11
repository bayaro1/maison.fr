<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Pro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pro>
 *
 * @method Pro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pro[]    findAll()
 * @method Pro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pro::class);
    }

    public function findByDepartmentAndCategoryHydratedWithFirstPicture(int $department, Category $category)
    {
        /** @var CategoryRepository */
        $categoryRepository = $this->getEntityManager()->getRepository(Category::class);

        /** @var PictureRepository */
        $pictureRepository = $this->getEntityManager()->getRepository(Picture::class);
        
        $pros = $this->createQueryBuilder('p')
                ->select('p', 'c')
                ->join('p.categories', 'c')
                ->andWhere('p.id IN(:ids)')
                ->setParameter('ids', $categoryRepository->findProIdsForOneCategory($category))
                ->andWhere('p.departments LIKE :department')
                ->setParameter('department', '%'.$department.'%')
                ->getQuery()
                ->getResult()
                ;
        $pictureRepository->hydrateProsWithFirstPicture($pros);
        return $pros;
    }

    public function add(Pro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Pro[] Returns an array of Pro objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pro
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
