<?php

declare(strict_types=1);
namespace deceitya\ShopAPI\database;

use Deceitya\MiningLevel\MiningLevelAPI;
use Exception;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\player\Player;

class EffectShopAPI {

    private static EffectShopAPI $instance;
    protected array $buy = [];
    protected array $sell = [];
    protected array $level = [];
    protected array $type = [];
    protected array $list = [];

    public function __construct() {
        $this->init();
    }

    protected function init(): void {
        $this->register(VanillaBlocks::REDSTONE_COMPARATOR()->asItem(), 2500, 0, 250, "block");##Redstone_Comparator
    }

    public function register(Item $item, int $buy, int $sell, int $level, string $type): void {
        $this->buy[$item->getId()][$item->getMeta()] = $buy;
        $this->sell[$item->getId()][$item->getMeta()] = $sell;
        $this->level[$item->getId()][$item->getMeta()] = $level;
        $this->type[$item->getId()][$item->getMeta()] = $type;
    }

    public function getBuy(int $id, ?int $meta = null): ?int {
        try {
            return $this->buy[$id][$meta ?? 0] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getSell(int $id, ?int $meta = null): ?int {
        try {
            return $this->sell[$id][$meta ?? 0] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkLevel(Player $player, int $id, ?int $meta = null): string {
        $miningLevel = MiningLevelAPI::getInstance();
        try {
            if (!($this->getLevel($id, $meta) < $miningLevel->getLevel($player->getName()))) {
                return "failure";
            }
            return "success";
        } catch (Exception $e) {
            return "exception";
        }
    }

    public static function getInstance(): EffectShopAPI {
        if (!isset(self::$instance)) {
            self::$instance = new EffectShopAPI();
        }
        return self::$instance;
    }

    public function getLevel(int $id, ?int $meta = null): ?int {
        try {
            return $this->level[$id][$meta ?? 0] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkType(int $id, ?int $meta = null): ?string {
        try {
            return $this->type[$id][$meta ?? 0] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }
}