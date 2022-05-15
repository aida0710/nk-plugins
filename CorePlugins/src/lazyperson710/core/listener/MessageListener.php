<?php

namespace lazyperson710\core\listener;

use bbo51dog\announce\service\AnnounceService;
use Deceitya\MiningLevel\MiningLevelAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class MessageListener implements Listener {

    public function join(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $event->getPlayer()->getName();
        $level = MiningLevelAPI::getInstance()->getLevel($player);
        $money = EconomyAPI::getInstance()->myMoney($player);
        if (!is_int($money)) {
            EconomyAPI::getInstance()->setMoney($player, floor($money));
        }
        if (!$player->hasPlayedBefore()) {
            $pos = new Position(245, 113, 246, Server::getInstance()->getWorldManager()->getWorldByName("tos"));
            $event->getPlayer()->teleport($pos);
            $pickaxe = ItemFactory::getInstance()->get(ItemIds::IRON_PICKAXE);
            $pickaxe->setCustomName("ﾃﾂぴっける");
            $pickaxe->setLore([
                "lore1" => "初回限定ピッケル",
                "lore2" => "サーバーを楽しんでください！",
            ]);
            if (!$player->getInventory()->contains($pickaxe) && $player->getInventory()->canAddItem($pickaxe)) {
                $player->getInventory()->addItem($pickaxe);
            }
            $axe = ItemFactory::getInstance()->get(ItemIds::GOLD_AXE);
            $axe->setCustomName("きんのおのぉー");
            $axe->setLore([
                "lore1" => "初回限定斧",
                "lore2" => "耐久力が乏しい・・・",
            ]);
            if (!$player->getInventory()->contains($axe) && $player->getInventory()->canAddItem($axe)) {
                $player->getInventory()->addItem($axe);
            }
            $event->setJoinMessage("§l§bNewPlayer §7>> §e{$name}さんが初めてサーバーに参加しました！");
        } elseif ($player->getName() === "hakokokku") {
            $event->setJoinMessage("§bHakokokkuLogin §7>> §e飴玉さんがログインしました！！！はこんって言ってみよう！MiningLv.{$level}");
        } else {
            if (AnnounceService::isConfirmed($player->getName())) {
                $event->setJoinMessage("§bLogin §7>> §e{$name}がログインしました。MiningLv.{$level}|Ping {$player->getNetworkSession()->getPing()}ms");
            } else {
                $event->setJoinMessage("§bLogin §7>> §e{$name}がログインしました。Passが認証されていません。");
                $pos = new Position(245, 113, 246, Server::getInstance()->getWorldManager()->getWorldByName("tos"));
                $event->getPlayer()->teleport($pos);
            }
        }
    }

    public function PlayerDeath(PlayerDeathEvent $event) {
        $event->setKeepInventory(true);
    }

    public function Hit(ProjectileHitEvent $event) {
        $entity = $event->getEntity();
        $entity->kill();
    }

    public function onUse(PlayerInteractEvent $event) {
        if ($event->getPlayer()->getInventory()->getItemInHand()->getId() === -195) {
            $event->cancel();
            Server::getInstance()->dispatchCommand($event->getPlayer(), "bonus");
        }
    }

    public function quit(PlayerQuitEvent $event) {
        $name = $event->getPlayer()->getName();
        $reason = $event->getQuitReason();
        if ($reason === 'client disconnect') {
            $event->setQuitMessage("§bLogout §7>> §e{$name}がサーバーを退出しました。Ping {$event->getPlayer()->getNetworkSession()->getPing()}ms");
        } elseif ($reason === 'timeout') {
            $event->setQuitMessage("§bLogout §7>> §e{$name}がタイムアウトしサーバーを退出しました。");
        } else {
            $event->setQuitMessage("§bLogout §7>> §e{$name}がサーバーを退出しました。");
        }
    }

    public function onplace(BlockPlaceEvent $event) {
        if ($event->getBlock()->getId() === 449) {//書見台
            $event->getPlayer()->sendMessage("§bBook §7>> §c本は土地保護されていても取れるので注意してください");
        }
    }

    public function kick(PlayerKickEvent $event) {
        $player = $event->getPlayer();
        $reason = $event->getReason();
        if ($reason === 'Server is white-listed') {
            $player->kick("§a現在サーバーはメンテナンス中です\n詳細はDiscordをご覧ください\n\nまた、不自然に思った場合TwitterDmへお越しください @lazyperson0710", false);
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        switch ($cause->getCause()) {
            case EntityDamageEvent::CAUSE_CONTACT:
                if ($cause instanceof EntityDamageByBlockEvent) {
                    $block = $cause->getDamager()->getName();
                    $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$block}によって圧死しました");
                } else {
                    $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は不明なブロックによって圧死しました");
                }
                break;
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
                $killer = $cause->getDamager();
                if ($killer instanceof Player) {
                    $itemHand = $killer->getInventory()->getItemInHand();
                    $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$killer->getName()}によって{$itemHand}で殺害されました");
                } else {
                    $event->setDeathMessage("§bDeath §7>> §e{$killer->getName()}は何かを殺害しました");
                }
                break;
            case EntityDamageEvent::CAUSE_PROJECTILE:
                $killer = $cause->getDamager();
                if ($killer instanceof Player) {
                    $bow = $killer->getInventory()->getItemInHand()->getName();
                    $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}は{$killer->getName()}によって{$bow}で殺害されました");
                } else {
                    $event->setDeathMessage("§bDeath §7>> §e{$killer->getName()}は何かを殺害しました");
                }
                break;
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が窒息死しました");
                break;
            case EntityDamageEvent::CAUSE_FALL:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が高所から落下しました");
                break;
            case EntityDamageEvent::CAUSE_FIRE:
            case EntityDamageEvent::CAUSE_FIRE_TICK:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が炎上ダメージによって死亡しました");
                break;
            case EntityDamageEvent::CAUSE_LAVA:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が溶岩遊泳しようとして死亡しました");
                break;
            case EntityDamageEvent::CAUSE_DROWNING:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が溺死しました");
                break;
            case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
            case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が爆発四散しました");
                break;
            case EntityDamageEvent::CAUSE_VOID:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が奈落に落下しました");
                break;
            case EntityDamageEvent::CAUSE_SUICIDE:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が自害しました");
                break;
            case EntityDamageEvent::CAUSE_MAGIC:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が呪文によって死亡しました");
                break;
            default:
                $event->setDeathMessage("§bDeath §7>> §e{$player->getName()}が死亡しました");
                break;
        }
    }

}