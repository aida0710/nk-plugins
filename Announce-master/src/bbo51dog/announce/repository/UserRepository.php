<?php

namespace bbo51dog\announce\repository;

use bbo51dog\announce\repository\dto\UserDto;

interface UserRepository extends Repository {

    public function getUser(string $name): UserDto;

    public function register(UserDto $dto): void;

    public function exists(string $name): bool;

    public function update(UserDto $dto): void;

    public function updateAllUserAnnounce(int $announceId): void;
}