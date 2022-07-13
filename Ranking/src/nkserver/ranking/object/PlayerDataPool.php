<?php

declare(strict_types = 1);
namespace nkserver\ranking\object;

use nkserver\ranking\Main;
use pocketmine\block\Block;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\World;
use RuntimeException;

abstract class PlayerDataPool {

    /** @var PlayerDataArray[] */
    protected static array $players_data = [];

    final private function __construct() {
        /** NOOP */
    }

    public static function init(): void {
        foreach ((new Config(Main::getDataPath() . 'PlayerData.json', Config::JSON))->getAll() as $name => $array) {
            self::$players_data[$name] = new PlayerDataArray($array);
        }
    }

    /** @return PlayerDataArray[] */
    public static function getAll(): array {
        return self::$players_data;
    }

    public static function finalize(): void {
        $conf = new Config(Main::getDataPath() . 'PlayerData.json', Config::JSON);
        $array = [];
        foreach (self::$players_data as $name => $data_array) {
            $array[$name] = $data_array->getAll();
        }
        $conf->setAll($array);
        $conf->save();
    }

    /** @return string[] */
    public static function getAllPlayers(): array {
        return array_keys(self::$players_data);
    }

    public static function onBlockBreak(Player $player, Block $block, World $world): void {
        self::getDataNonNull($player)->onBlockBreak($block, $world);
    }

    public static function getDataNonNull(Player $player): PlayerDataArray {
        if (!self::isRegistered($player)) self::register($player, true);
        return self::getData($player) ?? throw new RuntimeException('正常に登録処理が出来ませんでした');
    }

    public static function isRegistered(Player $player): bool {
        return isset(self::$players_data[strtolower($player->getName())]);
    }

    public static function register(Player $player, bool $force = false): void {
        if (!$force and self::isRegistered($player)) return;
        self::$players_data[strtolower($player->getName())] = new PlayerDataArray;
    }

    public static function getData(Player $player): ?PlayerDataArray {
        return self::getDataByName($player->getName());
    }

    public static function getDataByName(string $name): ?PlayerDataArray {
        return isset(self::$players_data[strtolower($name)]) ? self::$players_data[strtolower($name)] : null;
    }

    public static function onBlockPlace(Player $player, Block $block, World $world): void {
        self::getDataNonNull($player)->onBlockPlace($block, $world);
    }

    public static function onChat(Player $player): void {
        self::getDataNonNull($player)->onChat();
    }

    public static function onDeath(Player $player): void {
        self::getDataNonNull($player)?->onDeath();
    }
}