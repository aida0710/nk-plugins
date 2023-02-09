<?php

declare(strict_types = 1);
namespace bbo51dog\announce\repository;

use bbo51dog\announce\repository\dto\AnnounceDto;

interface AnnounceRepository extends Repository {

	public function getAnnounce(int $id) : ?AnnounceDto;

	/**
	 * @return int id of the announce
	 */
	public function register(AnnounceDto $dto) : int;

	public function exists(int $id) : bool;

	public function getLatestAnnounce() : ?AnnounceDto;

	public function getOldestAnnounce() : ?AnnounceDto;
}
