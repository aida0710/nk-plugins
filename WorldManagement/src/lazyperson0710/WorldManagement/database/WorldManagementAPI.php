<?php

declare(strict_types = 1);
namespace lazyperson0710\WorldManagement\database;

use pocketmine\Server;

class WorldManagementAPI {

    private static WorldManagementAPI $instance;
    protected array $heightLimit = [];
    protected array $miningLevelLimit = [];
    protected array $flyLimit = [];
    protected array $x1 = [];
    protected array $x2 = [];
    protected array $z1 = [];
    protected array $z2 = [];

    /**
     * @return void
     */
    public function init(): void {
        #public
        $this->register("lobby", 255, 0, 0, 320, 280, 230, 80);
        $this->register("tos", 255, 0, 0, 260, 260, 200, 230);
        $this->register("athletic", 255, 10, 0);
        $this->register("pvp", 255, 30, 0, 330, 268, 224, 162);
        #nature
        $this->register("nature-1", 120, 0, 130, 15000, 15000, -15000, -15000);
        $this->register("nature-2", 120, 0, 130, 15000, 15000, -15000, -15000);
        $this->register("nature-3", 120, 0, 130, 15000, 15000, -15000, -15000);
        $this->register("nature-4", 120, 15, 130, 25000, 25000, -25000, -25000);
        $this->register("nature-5", 120, 50, 130, 25000, 25000, -25000, -25000);
        $this->register("nature-6", 120, 150, 130, 45000, 45000, -45000, -45000);
        $this->register("nature-7", 120, 250, 130, 45000, 45000, -45000, -45000);
        $this->register("nature-8", 120, 350, 130, 65000, 65000, -65000, -65000);
        $this->register("nature-java", 180, 15, 255, 15000, 15000, -15000, -15000);
        $this->register("MiningWorld", 120, 30, 130, 8000, 8000, -8000, -8000);
        #nether
        $this->register("nether-1", 255, 15, 255, 15000, 15000, -15000, -15000);
        $this->register("nether-2", 255, 20, 255, 25000, 25000, -25000, -25000);
        $this->register("nether-3", 255, 20, 255, 45000, 45000, -45000, -45000);
        #end
        $this->register("end-1", 120, 200, 130, 1000, 1000, -1000, -1000);
        $this->register("end-2", 120, 200, 130, 1000, 1000, -1000, -1000);
        $this->register("end-3", 120, 200, 130, 1000, 1000, -1000, -1000);
        #resource
        $this->register("resource", 51, 30, 65, 96, 260, 260, 423);
        #生活ワールド
        $this->register("生物市-c", 51, 10, 120, 410, 412, 198, 200);
        $this->register("船橋市-c", 255, 10, 255, 497, 497, -498, -498);
        #農業ワールド
        $this->register("浜松市-f", 15, 20, 90, 555, 555, 251, 251);
        $this->register("八街市-f", 255, 80, 90, 822, 321, 257, -334);
    }

    /**
     * @param string $worldName
     * @param int $heightLimit
     * @param int $miningLevelLimit
     * @param int|null $flyLimit
     * @param int|null $x1
     * @param int|null $z1
     * @param int|null $x2
     * @param int|null $z2
     * @return void
     */
    public function register(string $worldName, int $heightLimit, int $miningLevelLimit, ?int $flyLimit = 300, ?int $x1 = 15000, ?int $z1 = 15000, ?int $x2 = -15000, ?int $z2 = -15000): void {
        if (Server::getInstance()->getWorldManager()->getWorldByName($worldName) === null) {
            Server::getInstance()->getLogger()->error("WorldManagementAPI: World " . $worldName . " not found.");
        }
        $this->heightLimit[$worldName] = $heightLimit;
        $this->miningLevelLimit[$worldName] = $miningLevelLimit;
        $this->flyLimit[$worldName] = $flyLimit;
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
        if (array_key_exists($worldFolderName, $this->heightLimit)) {
            return $this->heightLimit[$worldFolderName];
        }
        return 255;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getMiningLevelLimit(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->miningLevelLimit)) {
            return $this->miningLevelLimit[$worldFolderName];
        }
        return 0;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getFlyLimit(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->flyLimit)) {
            return $this->flyLimit[$worldFolderName];
        }
        return 300;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitX_1(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->x1)) {
            return $this->x1[$worldFolderName];
        }
        return 15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitX_2(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->x2)) {
            return $this->x2[$worldFolderName];
        }
        return -15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitZ_1(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->z1)) {
            return $this->z1[$worldFolderName];
        }
        return 15000;
    }

    /**
     * @param string $worldFolderName
     * @return int
     */
    public function getWorldLimitZ_2(string $worldFolderName): int {
        if (array_key_exists($worldFolderName, $this->z2)) {
            return $this->z2[$worldFolderName];
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