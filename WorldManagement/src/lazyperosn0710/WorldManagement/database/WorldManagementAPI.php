<?php

declare(strict_types = 1);
namespace lazyperson0710\WorldManagement\database;

use pocketmine\Server;
use pocketmine\world\World;

class WorldManagementAPI {

    private static WorldManagementAPI $instance;
    protected array $heightLimit = [];
    protected array $miningLevelLimit = [];
    protected array $x1 = [];
    protected array $x2 = [];
    protected array $z1 = [];
    protected array $z2 = [];

    /**
     * @return void
     */
    public function init(): void {
        $world = Server::getInstance()->getWorldManager();
        #public
        $this->register($world->getWorldByName("lobby"), 0, 0);
        $this->register($world->getWorldByName("tos"), 0, 0);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("pvp"), 0, 30);
        #nature
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
        $this->register($world->getWorldByName("athletic"), 0, 10);
    }

    /**
     * @param World $world
     * @param int $heightLimit
     * @param int $miningLevelLimit
     * @param int|null $x1
     * @param int|null $x2
     * @param int|null $z1
     * @param int|null $z2
     * @return void
     */
    public function register(World $world, int $heightLimit, int $miningLevelLimit, ?int $x1 = 15000, ?int $x2 = -15000, ?int $z1 = 15000, ?int $z2 = -15000): void {
        $worldName = $world->getFolderName();
        $this->heightLimit[$worldName] = $heightLimit;
        $this->miningLevelLimit[$worldName] = $miningLevelLimit;
        $this->x1[$worldName] = $x1;
        $this->x2[$worldName] = $x2;
        $this->z1[$worldName] = $z1;
        $this->z2[$worldName] = $z2;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getHeightLimit(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->heightLimit[$worldFolderName])) {
            return $this->heightLimit[$worldFolderName];
        }
        return 255;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getMiningLevelLimit(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->miningLevelLimit[$worldFolderName])) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return 0;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitX_1(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->miningLevelLimit[$worldFolderName])) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return 15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitX_2(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->miningLevelLimit[$worldFolderName])) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return -15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitZ_1(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->miningLevelLimit[$worldFolderName])) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return 15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitZ_2(string $worldFolderName): int {
        if (in_array($worldFolderName, $this->miningLevelLimit[$worldFolderName])) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return -15000;
    }

    /**
     * @return WorldManagementAPI
     */
    public static function getInstance(): WorldManagementAPI {
        if (!isset(self::$instance)) {
            self::$instance = new WorldManagementAPI();
        }
        return self::$instance;
    }

}