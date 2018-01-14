<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Entity;
use AppBundle\Repository\BaseRepository;
use Doctrine\ORM\EntityManager;

abstract class BaseManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var BaseRepository
     */
    protected $repository;

    public function __construct(EntityManager $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($entityClass);
    }

    public function save(Entity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

}