<?php

namespace lazyperson0710\EffectItems\tools\all;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class TreasureDiscoveryTools implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('TreasureDiscoveryTools') !== null) {//TreasureDiscoveryTools
            if (mt_rand(1, 500) === 250) {
                switch (rand(1, 10)) {
                    case 1:
                        EconomyAPI::getInstance()->addMoney($player, 150);
                        $player->sendTitle("お宝発見！！！", "150円をゲットした！");
                        break;
                    case 2:
                        EconomyAPI::getInstance()->addMoney($player, 800);
                        $player->sendTitle("お宝発見！！！", "800円をゲットした！");
                        break;
                    case 3:
                        EconomyAPI::getInstance()->addMoney($player, 15000);
                        $player->sendTitle("お宝発見！！！", "1500円をゲットした！");
                        break;
                    case 4:
                        EconomyAPI::getInstance()->addMoney($player, 5000);
                        $player->sendTitle("お宝発見！！！", "5000円をゲットした！");
                        break;
                    case 5:
                        EconomyAPI::getInstance()->addMoney($player, 25000);
                        $player->sendTitle("お宝発見！！！", "2.5万円をゲットした！");
                        break;
                    case 6:
                        EconomyAPI::getInstance()->addMoney($player, 50000);
                        $player->sendTitle("お宝発見！！！", "5万円をゲットした！");
                        break;
                    case 7:
                        EconomyAPI::getInstance()->addMoney($player, 100000);
                        $player->sendTitle("お宝発見！！！", "10万円をゲットした！");
                        break;
                    case 8:
                        EconomyAPI::getInstance()->addMoney($player, 150000);
                        $player->sendTitle("お宝発見！！！", "15万円をゲットした！");
                        break;
                    case 9:
                        EconomyAPI::getInstance()->addMoney($player, 250000);
                        $player->sendTitle("お宝発見！！！", "25万円をゲットした！");
                        break;
                    case 10:
                        EconomyAPI::getInstance()->addMoney($player, 500000);
                        $player->sendTitle("お宝発見！！！", "50万円をゲットした！");
                        break;
                    default:
                        return;
                }
                $player->getInventory()->removeItem($inHand);
                $sound = new PlaySoundPacket();
                $sound->soundName = "random.break";
                $sound->x = $player->getPosition()->getX();
                $sound->y = $player->getPosition()->getY();
                $sound->z = $player->getPosition()->getZ();
                $sound->volume = 1;
                $sound->pitch = 1;
                $player->getNetworkSession()->sendDataPacket($sound);
            }
        }
    }
}
