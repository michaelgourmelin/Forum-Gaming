<?php

namespace App\Repository;

use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\DependencyInjection\KnpPaginatorExtension;
use Knp\Component\Pager\Paginator;

/**
 * @extends ServiceEntityRepository<Theme>
 *
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    public function save(Theme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Theme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Theme[] Returns an array of Theme objects
     */
    public function paginationQuery(string $slug)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.categories', 'c')
            ->leftJoin('t.visits', 'v')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('t.created_at', 'DESC')
            ->getQuery();
    }

   /**
     * @return Theme[] Returns an array of Theme objects
     */
    public function findOneByName($search)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.visits', 'v')
            ->where('t.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('t.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
