<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository;

use Exception;
use function array_key_exists;

class RepositoryPool {

	/** @var Repository[] */
	private array $repositories = [];

	public function register(string $key, Repository $repo) {
		$this->repositories[$key] = $repo;
	}

	public function close() {
		foreach ($this->repositories as $repository) {
			$repository->close();
		}
	}

	/**
	 * @throws Exception
	 */
	public function getRepository(string $className) : Repository {
		if (!array_key_exists($className, $this->repositories)) {
			throw new Exception("Repository $className not found");
		}
		return $this->repositories[$className];
	}
}
