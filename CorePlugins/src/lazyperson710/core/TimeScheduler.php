<?php

namespace lazyperson710\core;

use onebone\economyapi\EconomyAPI;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class TimeScheduler extends Task {

    public function onRun(): void {
        //$dtStr = date("H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);
        //var_dump("{$dtStr} - 1秒");
        $count = count(Server::getInstance()->getOnlinePlayers());
        $color = rand(1, 2);
        switch ($color) {
            case 1:
                $color = "§e";
                break;
            case 2:
                $color = "§g";
                break;
        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $checkHASTE = $player->getEffects()->get(VanillaEffects::HASTE());
            $checkNIGHT_VISION = $player->getEffects()->get(VanillaEffects::NIGHT_VISION());
            $checkSATURATION = $player->getEffects()->get(VanillaEffects::SATURATION());
            switch ($count) {
                case 8:
                    $bonus = "{$color}同時ログイン数8以上の為\n暗視エフェクト付与&毎秒1円贈与中！";
                    if ($checkNIGHT_VISION === null) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkNIGHT_VISION->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    }
                    EconomyAPI::getInstance()->addMoney($player, 1);
                    Server::getInstance()->broadcastPopup($bonus);
                    break;
                case 9:
                    $bonus = "{$color}同時ログイン数9以上の為\n満腹&暗視エフェクト付与&毎秒1円贈与中！";
                    if ($checkNIGHT_VISION === null) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkNIGHT_VISION->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    }
                    if ($checkSATURATION === null) {
                        $effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkSATURATION->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    }
                    EconomyAPI::getInstance()->addMoney($player, 1);
                    Server::getInstance()->broadcastPopup($bonus);
                    break;
                case 10:
                case 11:
                    $bonus = "{$color}同時ログイン数10以上の為\n採掘上昇&満腹&暗視エフェクト付与&毎秒3円贈与中！";
                    if ($checkHASTE === null) {
                        $effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkHASTE->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
                        $player->getEffects()->add($effect);
                    }
                    if ($checkNIGHT_VISION === null) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkNIGHT_VISION->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    }
                    if ($checkSATURATION === null) {
                        $effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    } elseif ($checkSATURATION->getDuration() < 299) {
                        $effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
                        $player->getEffects()->add($effect);
                    }
                    EconomyAPI::getInstance()->addMoney($player, 3);
                    Server::getInstance()->broadcastPopup($bonus);
                    break;
            }
            if ($count >= 12) {
                $bonus = "{$color}同時ログイン数12以上の為\n採掘上昇&満腹&暗視エフェクト付与&毎秒5円贈与中！";
                if ($checkHASTE === null) {
                    $effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
                    $player->getEffects()->add($effect);
                } elseif ($checkHASTE->getDuration() < 299) {
                    $effect = new EffectInstance(VanillaEffects::HASTE(), 300, 1, false);
                    $player->getEffects()->add($effect);
                }
                if ($checkNIGHT_VISION === null) {
                    $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                    $player->getEffects()->add($effect);
                } elseif ($checkNIGHT_VISION->getDuration() < 299) {
                    $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                    $player->getEffects()->add($effect);
                }
                if ($checkSATURATION === null) {
                    $effect = new EffectInstance(VanillaEffects::SATURATION(), 300, 0, false);
                    $player->getEffects()->add($effect);
                } elseif ($checkSATURATION->getDuration() < 299) {
                    $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0, false);
                    $player->getEffects()->add($effect);
                }
                EconomyAPI::getInstance()->addMoney($player, 5);
                Server::getInstance()->broadcastPopup($bonus);
            }
        }
    }
}
