<?php

namespace deceitya\repair\form;

use Deceitya\MiningLevel\MiningLevelAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilBreakSound;
use pocketmine\world\sound\AnvilUseSound;

class RepairCommandForm implements Form {

    private int $level;
    private Durable $item;

    public function __construct(int $level, Durable $item) {
        $this->level = $level;
        $this->item = $item;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if (!$data) {
            $player->sendMessage('§bRepair §7>> §c修理をキャンセルしました');
            return;
        }
        if ($player->getXpManager()->getXpLevel() < $this->level) {
            $player->sendMessage("§bRepair §7>> §cレベルが足りませんでした。要求レベル->{$this->level}");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 100) {
            $this->item->setDamage(0);
            $player->getInventory()->setItemInHand($this->item);
            $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
            $player->getXpManager()->subtractXpLevels($this->level);
            $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 90) {
            if (mt_rand(1, 100) <= 1) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c1%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 80) {
            if (mt_rand(1, 100) <= 2) {
                EconomyAPI::getInstance()->reduceMoney($player, 1500);
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c2%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
        }
    }

    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'AnvilRepair',
            'content' => "消費レベルは{$this->level}です。\n\n本当に修理しますか？\n\n§l修理失敗確率§r\n80Lv以上90Lv未満->2％で破損\n90Lv以上100Lv未満->1％で破損\n§g100Lv以上は破損無し",
            'button1' => 'はい',
            'button2' => 'いいえ'
        ];
    }
}
