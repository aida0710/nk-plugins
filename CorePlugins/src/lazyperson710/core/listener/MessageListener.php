<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use bbo51dog\announce\service\AnnounceService;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\CoordinateSetting;
use lazyperson710\core\packet\CoordinatesPacket;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\Server;
use pocketmine\world\Position;

class MessageListener implements Listener {

    public function join(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $event->getPlayer()->getName();
        $level = MiningLevelAPI::getInstance()->getLevel($player);
        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(CoordinateSetting::getName())?->getValue() === true) {
            CoordinatesPacket::Send($player, true);
        } else {
            CoordinatesPacket::Send($player, false);
        }
        if (!$player->hasPlayedBefore()) {
            $pos = new Position(245, 113, 246, Server::getInstance()->getWorldManager()->getWorldByName('tos'));
            $event->getPlayer()->teleport($pos);
            $pickaxe = ItemFactory::getInstance()->get(ItemIds::IRON_PICKAXE);
            $pickaxe->setCustomName('ﾃﾂぴっける');
            $pickaxe->setLore([
                'lore1' => '初回限定ピッケル',
                'lore2' => 'サーバーを楽しんでください！',
            ]);
            if (!$player->getInventory()->contains($pickaxe) && $player->getInventory()->canAddItem($pickaxe)) {
                $player->getInventory()->addItem($pickaxe);
            }
            $axe = ItemFactory::getInstance()->get(ItemIds::GOLD_AXE);
            $axe->setCustomName('きんのおのぉー');
            $axe->setLore([
                'lore1' => '初回限定斧',
                'lore2' => '耐久力が乏しい・・・',
            ]);
            if (!$player->getInventory()->contains($axe) && $player->getInventory()->canAddItem($axe)) {
                $player->getInventory()->addItem($axe);
            }
            $event->setJoinMessage("§l§bNewPlayer §7>> §e{$name}さんが初めてサーバーに参加しました！。Ping {$player->getNetworkSession()->getPing()}ms");
        } elseif ($player->getName() === 'hakokokku') {
            $event->setJoinMessage("§bHakokokkuLogin §7>> §eわん、つー、すりー！はこさんがきたぞー！MiningLv.{$level}|Ping {$player->getNetworkSession()->getPing()}ms");
        } elseif ($player->getName() === 'ReimariDarkness') {
            $event->setJoinMessage("§bReimariLogin §7>> §a整地を愛し、§b整地に愛された男、§cレーマリだー！MiningLv.{$level}|Ping {$player->getNetworkSession()->getPing()}ms");
        } elseif (AnnounceService::isConfirmed($player->getName())) {
            $event->setJoinMessage("§bLogin §7>> §e{$name}がログインしました。MiningLv.{$level}|Ping {$player->getNetworkSession()->getPing()}ms");
        } else {
            $event->setJoinMessage("§bLogin §7>> §e{$name}がログインしました。Passが認証されていません。Ping {$player->getNetworkSession()->getPing()}ms");
            $pos = new Position(245, 113, 246, Server::getInstance()->getWorldManager()->getWorldByName('tos'));
            $event->getPlayer()->teleport($pos);
        }
    }

    public function onUse(PlayerInteractEvent $event) {
        if ($event->getPlayer()->getInventory()->getItemInHand()->getId() === -195) {
            $event->cancel();
            Server::getInstance()->dispatchCommand($event->getPlayer(), 'bonus');
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

    public function onPlace(BlockPlaceEvent $event) {
        if ($event->getBlock()->getId() === 449) {//書見台
            SendMessage::Send($event->getPlayer(), '本は土地保護されていても取れるので注意してください', 'Lectern', false);
        }
    }

    public function kick(PlayerKickEvent $event) {
        $player = $event->getPlayer();
        $reason = $event->getReason();
        if ($reason === 'Server is whitelisted') {
            $player->kick("§a現在サーバーはメンテナンス中です\n詳細はDiscordをご覧ください");
        }
    }
}
