<?php

namespace lazyperson0710\PlayerSetting\object;

use lazyperson0710\PlayerSetting\Main;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use RuntimeException;

class PlayerDataPool {

    private static PlayerDataPool $instance;

    /** @var PlayerDataArray[] */
    protected static array $players_data = [];

    public static function init(): void {
        foreach ((new Config(Main::getDataPath() . 'PlayerSettingData.json', Config::JSON))->getAll() as $name => $array) {
            self::$players_data[$name] = new PlayerDataArray($array);
        }
    }

    /** @return PlayerDataArray[] */
    public static function getAll(): array {
        return self::$players_data;
    }

    public static function finalize(): void {
        $conf = new Config(Main::getDataPath() . 'PlayerSettingData.json', Config::JSON);
        $array = [];
        var_dump(self::$players_data);
        foreach (self::$players_data as $name => $data_array) {
            $array[$name] = $data_array->getAll();
        }
        $conf->setAll($array);
        $conf->save();
    }

    // /** @return string[] */
    // public static function getAllPlayers(): array {
    //     return array_keys(self::$players_data);
    // }
    //
    // public static function getDataNonNull(Player $player): PlayerDataArray {
    //     if (!self::isRegistered($player)) self::register($player, true);
    //     return self::getData($player) ?? throw new RuntimeException('正常に登録処理が出来ませんでした');
    // }
    public static function isRegistered(Player $player): bool {
        return isset(self::$players_data[strtolower($player->getName())]);
    }

    public static function register(Player $player, bool $force = false): void {
        if (!$force and self::isRegistered($player)) return;
        self::$players_data[strtolower($player->getName())] = new PlayerDataArray;
    }

    public static function getDataNonNull(Player $player): PlayerDataArray {
        if (!self::isRegistered($player)) self::register($player, true);
        return self::getData($player) ?? throw new RuntimeException('正常に登録処理が出来ませんでした');
    }

    public static function getData(Player $player): ?PlayerDataArray {
        return self::getDataByName($player->getName());
    }

    public static function getDataByName(string $name): ?PlayerDataArray {
        return self::$players_data[strtolower($name)] ?? null;
    }

    public function SetCoordinate(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetCoordinate($player, $changeMode);
    }

    public function SetJoinItems(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetJoinItems($player, $changeMode);
    }

    public function SetDirectDropItemStorage(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetDirectDropItemStorage($player, $changeMode);
    }

    public function SetLevelUpTitle(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetLevelUpTitle($player, $changeMode);
    }

    public function SetEnduranceWarning(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetEnduranceWarning($player, $changeMode);
    }

    public function SetDestructionSound(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetDestructionSound($player, $changeMode);
    }

    public function SetDiceMessage(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetDiceMessage($player, $changeMode);
    }

    public function SetPayCommandUse(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetPayCommandUse($player, $changeMode);
    }

    public function SetPlayerDropItem(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetPlayerDropItem($player, $changeMode);
    }

    public function SetOnlinePlayersEffects(Player $player, bool $changeMode): void {
        self::getDataNonNull($player)->SetOnlinePlayersEffects($player, $changeMode);
    }

    public static function getInstance(): PlayerDataPool {
        if (!isset(self::$instance)) {
            self::$instance = new PlayerDataPool();
        }
        return self::$instance;
    }

}