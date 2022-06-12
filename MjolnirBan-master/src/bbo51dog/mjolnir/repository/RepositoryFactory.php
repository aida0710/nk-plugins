<?php

namespace bbo51dog\mjolnir\repository;

abstract class RepositoryFactory {

    /** @var Repository[] */
    private array $repositories = [];

    public function getRepository(string $class): Repository {
        return $this->repositories[$class];
    }

    public function register(string $class, Repository $repository): self {
        $this->repositories[$class] = $repository;
        return $this;
    }

    public function close(): void {
        foreach ($this->repositories as $repository) {
            $repository->close();
        }
    }
}