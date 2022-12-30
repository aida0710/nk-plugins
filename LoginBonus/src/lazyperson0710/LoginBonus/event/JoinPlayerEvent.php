<?php

namespace lazyperson0710\LoginBonus\event;

use lazyperson0710\LoginBonus\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class JoinPlayerEvent implements Listener {

    public function PlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if (date("Y/m/d") !== date("Y/m/d", explode('.', $player->getLastPlayed() / 1000)[0])) {
            //一日以上たってたら
            self::check($player, true);
            return;
        }
        //一日未満でアイテムがあった場合
        if (Main::getInstance()->lastBonusDateConfig->exists($player->getName())) {
            self::check($player);
        }
    }

    public static function check(Player $player, ?bool $new = false): void {
        //一日以上経過していてもデータがある場合が存在する
        //データのみがある場合もある
        $lastBonus = 0;
        if (Main::getInstance()->lastBonusDateConfig->exists($player->getName())) {
            $lastBonus = Main::getInstance()->lastBonusDateConfig->get($player->getName());
            if ($new === true) {
                $lastBonus += 1;
            }
        } elseif ($new === true) {
            $lastBonus = 1;
        }
        if ($lastBonus === 0) {
            throw new \Error("ログインBonusのチェック処理の際に0が入力されました");
        }
        $item = Main::getInstance()->loginBonusItem;
        $item->setCount($lastBonus);
        if ($player->getInventory()->canAddItem($item)) {
            Main::getInstance()->lastBonusDateConfig->remove($player->getName());
            $player->getInventory()->addItem($item);
            \lazyperson710\core\listener\JoinPlayerEvent::$joinMessage[$player->getName()][] = "ログインボーナスを受け取りました";
        } else {
            Main::getInstance()->lastBonusDateConfig->set($player->getName(), $lastBonus);
            \lazyperson710\core\listener\JoinPlayerEvent::$joinMessage[$player->getName()][] = "インベントリに空きがないため、ログインボーナスを受け取れませんでした。/bonusから保留になっているログインボーナスアイテムを受け取ろう";
        }
        Main::getInstance()->lastBonusDateConfig->save();
    }
}