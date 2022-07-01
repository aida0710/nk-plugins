<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\event;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerToggleGlideEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerToggleSwimEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\world\World;

class PlayerEvent implements Listener {

    public array $blockLogTemp = [];

    private static PlayerEvent $playerEvent;

    public function __construct() {
        self::$playerEvent = $this;
    }

    /**
     * @priority MONITOR
     * @param BlockBreakEvent $event
     */
    public function blockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getBlock());
    }

    /**
     * @priority MONITOR
     * @param MiningToolsBreakEvent $event
     */
    public function miningToolsBreak(MiningToolsBreakEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getBlock());
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     */
    public function blockPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getBlock());
    }

    /**
     * @priority MONITOR
     * @param PlayerInteractEvent $event
     */
    public function playerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getBlock(), "ItemInHand - {$event->getPlayer()->getInventory()->getItemInHand()}");
    }

    /**
     * @priority MONITOR
     * @param PlayerItemUseEvent $event
     */
    public function playerItemUse(PlayerItemUseEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getItem());
    }

    /**
     * @priority MONITOR
     * @param PlayerGameModeChangeEvent $event
     */
    public function playerGameModeChange(PlayerGameModeChangeEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), others: "GameMode - {$event->getNewGamemode()->name()}");
    }

    /**
     * @priority MONITOR
     * @param PlayerExperienceChangeEvent $event
     */
    public function playerExperienceChange(PlayerExperienceChangeEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) {
            return;
        }
        /** @var Player $player */
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $player->getInventory()->getItemInHand(), "level - {$event->getOldLevel()}");
    }

    /**
     * @priority MONITOR
     * @param EntityDamageEvent $event
     */
    public function entityDamage(EntityDamageEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) {
            return;
        }
        /** @var Player $player */
        $damage = match ($event->getCause()) {
            0 => "CONTACT",
            1 => "ENTITY_ATTACK",
            2 => "PROJECTILE",
            3 => "SUFFOCATION",
            4 => "FALL",
            5 => "FIRE",
            6 => "FIRE_TICK",
            7 => "LAVA",
            8 => "DROWNING",
            9 => "BLOCK_EXPLOSION",
            10 => "ENTITY_EXPLOSION",
            11 => "VOID",
            12 => "SUICIDE",
            13 => "MAGIC",
            14 => "CUSTOM",
            15 => "STARVATION",
            default => "unknown",
        };
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $player->getInventory()->getItemInHand(), "damage - {$damage}");
    }

    /**
     * @priority MONITOR
     * @param PlayerToggleFlightEvent $event
     */
    public function playerToggleFlight(PlayerToggleFlightEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerToggleGlideEvent $event
     */
    public function playerToggleGlide(PlayerToggleGlideEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerToggleSneakEvent $event
     */
    public function playerToggleSneak(PlayerToggleSneakEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerToggleSprintEvent $event
     */
    public function playerToggleSprint(PlayerToggleSprintEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerToggleSwimEvent $event
     */
    public function playerToggleSwim(PlayerToggleSwimEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerDropItemEvent $event
     */
    public function playerDropItem(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getItem());
    }

    /**
     * @priority MONITOR
     * @param PlayerCommandPreprocessEvent $event
     */
    public function playerCommandPreprocess(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $player->getInventory()->getItemInHand(), "Command - {$event->getMessage()}");
    }

    /**
     * @priority MONITOR
     * @param PlayerDeathEvent $event
     */
    public function playerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getPlayer()->getInventory()->getItemInHand(), "deathMessage - {$event->getDeathMessage()}");
    }

    /**
     * @priority MONITOR
     * @param PlayerRespawnEvent $event
     */
    public function playerRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getPlayer()->getInventory()->getItemInHand());
    }

    /**
     * @priority MONITOR
     * @param PlayerJumpEvent $event
     */
    public function playerJump(PlayerJumpEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition(), $event->getPlayer()->getInventory()->getItemInHand());
    }

    /**
     * @priority MONITOR
     * @param PlayerLoginEvent $event
     */
    public function playerLogin(PlayerLoginEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerPreLoginEvent $event
     */
    public function playerPreLogin(PlayerPreLoginEvent $event) {
        $this->othersLog($event->getPlayerInfo()->getUsername(), $event->getEventName(), others: "KickMessage - {$event->getFinalKickMessage()}");
    }

    /**
     * @priority MONITOR
     * @param PlayerQuitEvent $event
     */
    public function playerQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @priority MONITOR
     * @param PlayerChangeSkinEvent $event
     */
    public function playerChangeSkin(PlayerChangeSkinEvent $event) {
        $player = $event->getPlayer();
        $this->othersLog($player->getName(), $event->getEventName(), $player->getWorld(), $player->getPosition());
    }

    /**
     * @param string $player
     * @param string $type
     * @param World|null $world
     * @param Position|null $position
     * @param Item|Block|null $item
     * @param string|null $others
     * @return void
     */
    public function othersLog(string $player, string $type, ?World $world = null, ?Position $position = null, Item|Block|null $item = null, ?string $others = null): void {
        if (!is_null($world)) {
            $world = $world->getFolderName();
        }
        if (!is_null($position)) {
            $positionX = $position->getFloorX();
            $positionY = $position->getFloorY();
            $positionZ = $position->getFloorZ();
        } else {
            $positionX = null;
            $positionY = null;
            $positionZ = null;
        }
        if (!is_null($item)) {
            $blockName = $item->getName();
            $blockId = $item->getId();
            $blockMeta = $item->getMeta();
        } else {
            $blockName = null;
            $blockId = null;
            $blockMeta = null;
        }
        $type = strrchr($type, '\\');
        $type = str_replace('\\', '', $type);
        $log[] = [
            "name" => $player,
            "type" => $type,
            "world" => $world,
            "x" => $positionX,
            "y" => $positionY,
            "z" => $positionZ,
            "blockName" => $blockName,
            "blockId" => $blockId,
            "blockMeta" => $blockMeta,
            "others" => $others,
            "date" => date("Y-m-d"),
            "time" => date("H:i:s") . "." . substr(explode(".", microtime())[1], 0, 3)
        ];
        $this->setBlockLogTemp($log);
    }

    /**
     * @return array
     */
    public function getBlockLogTemp(): array {
        return $this->blockLogTemp;
    }

    /**
     * @param array $blockLogTemp
     */
    public function setBlockLogTemp(array $blockLogTemp): void {
        $blockLogTemp = array_merge($blockLogTemp, $this->blockLogTemp);
        $this->blockLogTemp = $blockLogTemp;
    }

    /**
     * @return void
     */
    public function refreshBlockLogTemp(): void {
        $this->blockLogTemp = [];
    }

    /**
     * @return PlayerEvent
     */
    public static function getInstance(): PlayerEvent {
        return self::$playerEvent;
    }
}