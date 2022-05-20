<?php

namespace bbo51dog\announce\service;

use bbo51dog\announce\model\Announce;
use bbo51dog\announce\repository\AnnounceRepository;
use bbo51dog\announce\repository\dto\AnnounceDto;
use bbo51dog\announce\repository\dto\UserDto;
use bbo51dog\announce\repository\RepositoryPool;
use bbo51dog\announce\repository\UserRepository;

class AnnounceService {

    private static RepositoryPool $repositoryPool;

    private function __construct() {
    }

    public static function init(RepositoryPool $repositoryPool) {
        self::$repositoryPool = $repositoryPool;
    }

    public static function createAnnounce(string $content, int $type) {
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        $announceDto = new AnnounceDto($content, $type, time());
        $id = $announceRepository->register($announceDto);
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        $userRepository->updateAllUserAnnounce($id);
    }

    public static function exsitsAnnounce(int $announeId): bool {
        return self::getAnnounce($announeId) !== null;
    }

    public static function getAnnounce(int $announceId): ?Announce {
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        $dto = $announceRepository->getAnnounce($announceId);
        if ($dto instanceof AnnounceDto) {
            return Announce::createFromDto($dto);
        }
        return null;
    }

    public static function setAlreadyRead(string $name, bool $b) {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        $dto = $userRepository->getUser($name);
        $dto->setHasRead($b);
        $userRepository->update($dto);
    }

    public static function getAnnounceIdByName(string $name): int {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        return $userRepository->getUser($name)->getAnnounceId();
    }

    public static function getLatestAnnounceId(): ?int {
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        return $announceRepository->getLatestAnnounce()?->getId();
    }

    public static function getOldestAnnounceId(): ?int {
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        return $announceRepository->getOldestAnnounce()?->getId();
    }

    public static function canRead(string $name): bool {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        $userDto = $userRepository->getUser($name);
        return $announceRepository->exists($userDto->getAnnounceId()) && $userDto->isConfirmed();
    }

    public static function isConfirmed(string $name): bool {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        return $userRepository->getUser($name)->isConfirmed();
    }

    public static function hasAlreadyRead(string $name): bool {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        return $userRepository->getUser($name)->hasRead();
    }

    public static function createUser(string $name) {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        /** @var AnnounceRepository $announceRepository */
        $announceRepository = self::$repositoryPool->getRepository(AnnounceRepository::class);
        $announceId = $announceRepository->getLatestAnnounce()?->getId() ?: -1;
        $userRepository->register(new UserDto($name, false, $announceId));
    }

    public static function existsUser(string $name): bool {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        return $userRepository->exists($name);
    }

    public static function confirm(string $name, bool $b = true) {
        /** @var UserRepository $userRepository */
        $userRepository = self::$repositoryPool->getRepository(UserRepository::class);
        $dto = $userRepository->getUser($name);
        $dto->setConfirmed($b);
        $userRepository->update($dto);
    }
}