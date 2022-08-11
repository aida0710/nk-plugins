<?php

namespace lazyperson710\core\listener;

use bbo51dog\announce\service\AnnounceService;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\Server;
use pocketmine\world\Position;

class MessageListener implements Listener {

    public function join(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $pk = new GameRulesChangedPacket();
        $pk->gameRules = ["showcoordinates" => new BoolGameRule(true, false)];
        $player->getNetworkSession()->sendDataPacket($pk);
        $name = $event->getPlayer()->getName();
        $level = MiningLevelAPI::getInstance()->getLevel($player);
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
                $event->setJoinMessage("§bLogin §7>> §e{$name}がログインしました。Passが認証されていません");
                $pos = new Position(245, 113, 246, Server::getInstance()->getWorldManager()->getWorldByName("tos"));
                $event->getPlayer()->teleport($pos);
            }
        }
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
            $event->setQuitMessage("§bLogout §7>> §e{$name}がタイムアウトしサーバーを退出しました");
        } else {
            $event->setQuitMessage("§bLogout §7>> §e{$name}がサーバーを退出しました");
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
        if ($reason === 'Server is whitelisted') {
            $player->kick("§a現在サーバーはメンテナンス中です\n詳細はDiscordをご覧ください", false);
        }
    }

}