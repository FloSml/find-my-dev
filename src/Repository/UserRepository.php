<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAllLookingForJob()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.lookingForJob = true')
            ->getQuery()
            ->getResult();
    }

    public function findAllExperienceAsc()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.experience', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllExperienceDesc()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.experience', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $search
     * @return array
     */
    public function findMember($search = ''): Query
    {
        return $this->createQueryBuilder('u')
            ->Where('u.city LIKE :city')
            ->setParameter('city', '%'.$search.'%')
            ->orWhere('u.speciality LIKE :speciality')
            ->setParameter('speciality', '%'.$search.'%')
            ->orWhere('u.resume LIKE :resume')
            ->setParameter('resume', '%'.$search.'%')
            ->getQuery()
            ;
    }

    public function findAllMembersQuery(): Query
    {
        return $this->findAllMembers()
            ->getQuery();
    }

    private function findAllMembers(): QueryBuilder
    {
        return $this->createQueryBuilder('u');
    }

}
