<?php

declare(strict_types = 1);
namespace lazyperson0710\Gacha\database;

use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class GachaItemAPI {

    private static GachaItemAPI $instance;
    public const Category = [
        1 => "常駐ガチャ",
        2 => "Coming Soon...",
    ];
    public array $categoryCost = [];
    public array $rankProbability = [];
    public array $gachaItems = [];

    public function init(): void {
        $this->categorySettingRegister("常駐ガチャ", 0, 0, 60, 33, 6, 0.9, 0.1);
        $this->categorySettingRegister("Coming Soon...", 10000000000, 0, 70, 23, 6, 0.9, 0.1);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターc", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターc-2", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::NETHER_STAR(), 1, "ネザースターuc", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターuc-2", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターr-2", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターsr-2", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "ネザースターl-2", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::NETHER_STAR(), 1, "ネザースターr", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "SR", VanillaItems::NETHER_STAR(), 1, "ネザースターsr", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
        $this->dischargeItemRegister("常駐ガチャ", "L", VanillaItems::NETHER_STAR(), 1, "ネザースターl", "ただの石", [VanillaEnchantments::INFINITY()], [1], ["test", "test1"]);
    }

    private function categorySettingRegister(string $category, int $moneyCost, int $ticketCost, float $C, float $UC, float $R, float $SR, float $L): void {
        $this->categoryCost[$category][] = ["moneyCost" => $moneyCost, "ticketCost" => $ticketCost];
        $this->rankProbability[$category][] = ["C" => $C, "UC" => $UC, "R" => $R, "SR" => $SR, "L" => $L];
    }

    private function dischargeItemRegister(string $category, string $rank, Item $item, int $count, string $name, string $lore, array $enchants, array $level, array $nbt): void {
        $this->gachaItems[$category][$rank][] = ["item" => $item, "count" => $count, "name" => $name, "lore" => $lore, "enchants" => $enchants, "level" => $level, "nbt" => $nbt];
    }

    /**
     * @return GachaItemAPI
     */
    public static function getInstance(): GachaItemAPI {
        if (!isset(self::$instance)) {
            self::$instance = new GachaItemAPI();
        }
        return self::$instance;
    }

}