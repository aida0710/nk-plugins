<?php

namespace lazyperson710\core\listener;

use lazyperson710\sff\form\WarpForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Server;

class DeathListener implements Listener {

    public function Death(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $see = EconomyAPI::getInstance()->myMoney($player);
        if ($see >= 2000) {
            if ($see >= 1000000) {
                $floor_money1000000 = floor($see / 2);
                EconomyAPI::getInstance()->reduceMoney($player, $floor_money1000000);
                Server::getInstance()->broadcastMessage("§bDeathMessage §7>> §e{$player->getName()}に死亡ペナルティーが適用されたため、所持金が{$see}円から{$floor_money1000000}円が徴収されました");
                return;
            }
            $floor_money = floor($see / 2);
            EconomyAPI::getInstance()->reduceMoney($player, $floor_money);
            $myMoney = EconomyAPI::getInstance()->myMoney($player);
            $result = 2000 - $myMoney;
            $result = - $result;
            if ($myMoney <= 1999){
               EconomyAPI::getInstance()->setMoney($player, 2000);
                $player->sendMessage("§bDeathMessage §7>> §a死亡ペナルティーが適用されたため、所持金が{$see}円から5割徴収される予定でしたが2000円以下になってしまう為{$result}円だけ徴収されました");
            } else {
                $player->sendMessage("§bDeathMessage §7>> §a死亡ペナルティーが適用されたため、所持金が{$see}円から{$floor_money}円が徴収されました");
            }
        } else {
            $player->sendMessage("§bDeathMessage §7>> §a所持金が2000円以下の{$see}円だっため死亡ペナルティーは経験値のみとなりました");
        }
        $world = $player->getWorld()->getFolderName();
        $floor_x = floor($player->getPosition()->getX());
        $floor_y = floor($player->getPosition()->getY());
        $floor_z = floor($player->getPosition()->getZ());
        $player->sendMessage("§bDeathMessage §7>> §a死亡地点は{$world}のx.{$floor_x},y.{$floor_y},z.{$floor_z}です");
    }

    public function Respawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        Server::getInstance()->broadcastTip("§bReSpawn §7>> §e{$name}がリスポーンしました");
        if ($player->getWorld()->getDisplayName() === "rule") {
            $player->sendForm(new WarpForm($player));
        }
    }
}