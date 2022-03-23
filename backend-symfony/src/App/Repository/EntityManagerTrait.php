<?php

declare(strict_types=1);

namespace App\Repository;

trait EntityManagerTrait
{
    public function save(object $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function delete(object $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    public function clear(): void
    {
        $this->_em->clear();
    }
}
