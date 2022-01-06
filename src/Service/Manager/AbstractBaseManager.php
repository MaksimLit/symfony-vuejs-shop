<?php declare(strict_types = 1);

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class AbstractBaseManager
 */
abstract class AbstractBaseManager
{
    protected EntityManagerInterface $entityManager;

    /**
     * AbstractBaseManager constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ObjectRepository
     */
    public abstract function getRepository(): ObjectRepository;

    /**
     * @param $id
     * @return object|null
     */
    public function find($id): ?object
    {
        return $this->getRepository()->find(['id' => $id]);
    }

    /**
     * @param Object $entity
     */
    public function persistAndFlush(object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Object $entity
     */
    public function remove(object $entity)
    {
        $this->entityManager->remove($entity);
        $this->persistAndFlush($entity);
    }
}