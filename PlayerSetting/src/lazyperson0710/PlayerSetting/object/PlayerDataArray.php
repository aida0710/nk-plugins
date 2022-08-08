<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object;

use pocketmine\player\Player;

class PlayerDataArray {

    protected array $array = [];
    private static PlayerDataArray $instance;

    protected const Coordinate = 'Coordinate';
    protected const JoinItems = 'JoinItems';
    protected const DirectDropItemStorage = 'DirectDropItemStorage';//Inventoryが満タンじゃなくても採掘したもの全部突っ込む
    protected const LevelUpTitle = 'LevelUpTitle';
    protected const EnduranceWarning = 'EnduranceWarning';
    protected const DestructionSound = 'DestructionSound';//defaultでサウンドあり
    protected const DiceMessage = 'DiceMessage';          //defaultで表示
    protected const PayCommandUse = 'PayCommandUse';
    protected const PlayerDropItem = 'PlayerDropItem';
    protected const OnlinePlayersEffects = 'OnlinePlayersEffects';

    public function __construct(?array $array = null) {
        $this->array = $array ?? [
            self::Coordinate => true,
            self::JoinItems => true,
            self::DirectDropItemStorage => true,
            self::LevelUpTitle => true,
            self::EnduranceWarning => true,
            self::DestructionSound => true,
            self::DiceMessage => true,
            self::PayCommandUse => true,
            self::PlayerDropItem => true,
            self::OnlinePlayersEffects => true,
        ];
    }

    public function getAll(): array {
        return $this->array;
    }

    public function getCoordinate(Player $player): bool {
        return $this->array[self::Coordinate];
    }

    public function getJoinItems(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::JoinItems];
    }

    public function getDirectDropItemStorage(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::DirectDropItemStorage];
    }

    public function getLevelUpTitle(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::LevelUpTitle];
    }

    public function getEnduranceWarning(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::EnduranceWarning];
    }

    public function getDestructionSound(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::DestructionSound];
    }

    public function getDiceMessage(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::DiceMessage];
    }

    public function getPayCommandUse(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::PayCommandUse];
    }

    public function getPlayerDropItem(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::PlayerDropItem];
    }

    public function getOnlinePlayersEffects(Player $player): bool {
        return $this->array[strtolower($player->getName())][self::OnlinePlayersEffects];
    }

    public function SetCoordinate(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][][self::JoinItems] = $changeMode;
    }

    public function SetJoinItems(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::JoinItems] = $changeMode;
    }

    public function SetDirectDropItemStorage(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::DirectDropItemStorage] = $changeMode;
    }

    public function SetLevelUpTitle(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::LevelUpTitle] = $changeMode;
    }

    public function SetEnduranceWarning(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::EnduranceWarning] = $changeMode;
    }

    public function SetDestructionSound(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::DestructionSound] = $changeMode;
    }

    public function SetDiceMessage(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::DiceMessage] = $changeMode;
    }

    public function SetPayCommandUse(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::PayCommandUse] = $changeMode;
    }

    public function SetPlayerDropItem(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::PlayerDropItem] = $changeMode;
    }

    public function SetOnlinePlayersEffects(Player $player, bool $changeMode): void {
        $this->array[strtolower($player->getName())][self::OnlinePlayersEffects] = $changeMode;
    }

    public static function getInstance(): PlayerDataArray {
        if (!isset(self::$instance)) {
            self::$instance = new PlayerDataArray();
        }
        return self::$instance;
    }
}