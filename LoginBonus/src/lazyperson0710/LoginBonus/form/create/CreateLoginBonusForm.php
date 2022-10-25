<?php

namespace lazyperson0710\LoginBonus\form\create;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class CreateLoginBonusForm extends CustomForm {

    private Input $amount;
    private Toggle $onHolidayToggle;

    public function __construct() {
        $this->amount = new Input("ログインボーナスを生成する個数を入力してください", "1");
        $this->onHolidayToggle = new Toggle("保留リストに送る", false);
        $this
            ->setTitle("Login Bonus")
            ->addElements(
                $this->amount,
                $this->onHolidayToggle,
            );
    }

    public function handleSubmit(Player $player): void {
        $amount = $this->amount->getValue();
        $onHoliday = $this->onHolidayToggle->getValue();
        if (!is_numeric($amount)) {
            $player->sendMessage("§bLogin §7>> §c数字を入力してください");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($amount < 1) {
            $player->sendMessage("§bLogin §7>> §c1以上の数字を入力してください");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $amount = (int)$amount;
        if (Main::getInstance()->lastBonusDateConfig->exists($player->getName())) {
            $currentBonus = Main::getInstance()->lastBonusDateConfig->get($player->getName());
            $currentBonus += $amount;
        } else {
            $currentBonus = $amount;
        }
        if (!$onHoliday) {
            $item = Main::getInstance()->loginBonusItem;
            $item->setCount($currentBonus);
            if ($player->getInventory()->canAddItem($item)) {
                Main::getInstance()->lastBonusDateConfig->remove($player->getName());
                $player->getInventory()->addItem($item);
                $player->sendMessage("§bLogin §7>> §a{$currentBonus}個のログインボーナスを生成、付与しました");
                SoundPacket::Send($player, 'note.harp');
            } else {
                Main::getInstance()->lastBonusDateConfig->set($player->getName(), $currentBonus);
                $player->sendMessage("§bLogin §7>> §cインベントリに空きがないため、ログインボーナスを受け取れませんでした。/bonusから保留になっているログインボーナスアイテムを受け取ろう");
                SoundPacket::Send($player, 'note.bass');
            }
        } else {
            Main::getInstance()->lastBonusDateConfig->set($player->getName(), $currentBonus);
            $player->sendMessage("§bLogin §7>> §a保留リストに{$currentBonus}個のログインボーナスを追加しました");
            SoundPacket::Send($player, 'note.harp');
        }
        Main::getInstance()->lastBonusDateConfig->save();
    }

}