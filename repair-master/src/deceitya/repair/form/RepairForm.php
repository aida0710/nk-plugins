<?php
namespace deceitya\repair\form;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\form\Form;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilBreakSound;
use pocketmine\world\sound\AnvilUseSound;

class RepairForm implements Form {

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
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 70) {
            if (mt_rand(1, 100) <= 3) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c3%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 60) {
            if (mt_rand(1, 100) <= 4) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c4%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 50) {
            if (mt_rand(1, 100) <= 5) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c5%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 40) {
            if (mt_rand(1, 100) <= 6) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c6%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 30) {
            if (mt_rand(1, 100) <= 7) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c7%の確率で修理に修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 20) {
            if (mt_rand(1, 100) <= 8) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c8%の確率で修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 10) {
            if (mt_rand(1, 100) <= 9) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->removeItem($item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage('§bRepair §7>> §c9%の確率で修理に失敗したためアイテムが消滅しました。');
            } else {
                $this->item->setDamage(0);
                $player->getInventory()->setItemInHand($this->item);
                $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
                $player->getXpManager()->subtractXpLevels($this->level);
                $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
            }
        } elseif (mt_rand(1, 100) <= 10) {
            $item = $player->getInventory()->getItemInHand();
            $player->getInventory()->removeItem($item);
            $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
            $player->getXpManager()->subtractXpLevels($this->level);
            $player->sendMessage('§bRepair §7>> §c10%の確率で修理に失敗したためアイテムが消滅しました。');
        } else {
            $this->item->setDamage(0);
            $player->getInventory()->setItemInHand($this->item);
            $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
            $player->getXpManager()->subtractXpLevels($this->level);
            $player->sendMessage("§bRepair §7>> §a{$this->level}を消費してアイテムを修理しました。");
        }
    }

    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'AnvilRepair',
            'content' => "消費レベルは{$this->level}です。\n\n本当に修理しますか？\n\n§l修理失敗確率§r\n10Lv未満->10％で破損\n10Lv以上20Lv未満->9％で破損\n20Lv以上30Lv未満->8％で破損\n30Lv以上40Lv未満->7％で破損\n40Lv以上50Lv未満->6％で破損\n50Lv以上60Lv未満->5％で破損\n60Lv以上70Lv未満->4％で破損\n70Lv以上80Lv未満->3％で破損\n80Lv以上90Lv未満->2％で破損\n90Lv以上100Lv未満->1％で破損\n§g100Lv以上は破損無し",
            'button1' => 'はい',
            'button2' => 'いいえ'
        ];
    }
}
