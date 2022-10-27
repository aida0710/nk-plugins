<?php

namespace deceitya\repair\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use Exception;
use lazyperson710\core\packet\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\item\ToolTier;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilBreakSound;

class RepairForm extends CustomForm {

    private Toggle $ticket;
    private Durable $item;
    private int $level;
    private string $mode;

    public function __construct(int $level, Durable $item, string $mode) {
        $this->item = $item;
        $this->level = $level;
        $this->mode = $mode;
        $this->ticket = new Toggle("修繕費用代替インゴットを使用する\n(インベントリから取得)");
        if ($this->mode === "command") {
            $warning = new Label("ExpLevelを消費してツールを修繕することができます\n稀に修繕した際に破損する可能性があります\n破損確率は下記を参照\n\n消費レベル : {$this->level}\nまた、コマンドから表示している為、手数料として3500円徴収されます");
            $probability = new Label("§l修理失敗確率§r\n80Lv以上90Lv未満->2％で破損\n90Lv以上100Lv未満->1％で破損\n§g100Lv以上は破損無し");
        } else {
            $warning = new Label("ExpLevelを消費してツールを修繕することができます\n稀に修繕した際に破損する可能性があります\n破損確率は下記を参照\n\n消費レベル : {$level}");
            $probability = new Label("§l修理失敗確率§r\n10Lv未満->10％で破損\n10Lv以上20Lv未満->9％で破損\n20Lv以上30Lv未満->8％で破損\n30Lv以上40Lv未満->7％で破損\n40Lv以上50Lv未満->6％で破損\n50Lv以上60Lv未満->5％で破損\n60Lv以上70Lv未満->4％で破損\n70Lv以上80Lv未満->3％で破損\n80Lv以上90Lv未満->2％で破損\n90Lv以上100Lv未満->1％で破損\n§g100Lv以上は破損無し");
        }
        $this
            ->setTitle("Item Repair")
            ->addElements(
                $warning,
                $this->ticket,
                $probability,
            );
    }

    public function handleSubmit(Player $player): void {
        $consumption = "error";
        if ($this->checkItem($player) === false) {
            return;
        }
        if ($this->mode === "command") {
            if (EconomyAPI::getInstance()->myMoney($player->getName()) < 3500) {
                SendMessage::Send($player, "手数料分を取得できませんでした。手数料3500円", "Repair", false);
                return;
            }
        }
        if ($this->ticket->getValue() === true) {
            for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
                $item = clone $player->getInventory()->getItem($i);
                if ($item->getId() == ItemIds::AIR) continue;
                if ($item->getNamedTag()->getTag('repairIngot') !== null) {
                    $player->getInventory()->removeItem($item->setCount(1));
                    $consumption = "ingot";
                    break;
                }
            }
            if ($consumption === "error") {
                SendMessage::Send($player, "修理費用を取得できませんでした", "Repair", false);
                return;
            }
        } elseif ($player->getXpManager()->getXpLevel() < $this->level) {
            SendMessage::Send($player, "レベルが足りませんでした要求レベル->{$this->level}", "Repair", false);
            return;
        } else {
            $consumption = "level";
            $player->getXpManager()->subtractXpLevels($this->level);
        }
        if ($this->mode === "command") {
            EconomyAPI::getInstance()->reduceMoney($player->getName(), 3500);
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 100) {
            $this->itemRepair($player, $consumption);
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 90) {
            if (mt_rand(1, 100) <= 1) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "1%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 80) {
            if (mt_rand(1, 100) <= 2) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "2%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 70) {
            if (mt_rand(1, 100) <= 3) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "3%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 60) {
            if (mt_rand(1, 100) <= 4) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "4%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 50) {
            if (mt_rand(1, 100) <= 5) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "5%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 40) {
            if (mt_rand(1, 100) <= 6) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "6%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 30) {
            if (mt_rand(1, 100) <= 7) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "7%の確率で修理に修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 20) {
            if (mt_rand(1, 100) <= 8) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "8%の確率で修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 10) {
            if (mt_rand(1, 100) <= 9) {
                $this->itemDisappearance($player);
                SendMessage::Send($player, "9%の確率で修理に失敗したためアイテムが消滅しました", "Repair", false);
            } else {
                $this->itemRepair($player, $consumption);
            }
        } elseif (mt_rand(1, 100) <= 10) {
            $this->itemDisappearance($player);
            SendMessage::Send($player, "10%の確率で修理に失敗したためアイテムが消滅しました", "Repair", false);
        } else {
            $this->itemRepair($player, $consumption);
        }
    }

    public function itemDisappearance(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item);
        $player->getWorld()->addSound($player->getPosition(), new AnvilBreakSound());
    }

    public function itemRepair(Player $player, $consumption) {
        $this->item->setDamage(0);
        $player->getInventory()->setItemInHand($this->item);
        switch ($consumption) {
            case "ingot":
                SendMessage::Send($player, "インゴットを一個消費してアイテムを修理しました", "Repair", true, "random.anvil_use");
                break;
            case "level":
                SendMessage::Send($player, "Levelを{$this->level}消費してアイテムを修理しました", "Repair", true, "random.anvil_use");
                break;
            default:
                throw new \Error("不正な状態が保存された変数が処理されました");
        }
    }

    /**
     * @throws Exception
     */
    public static function checkItem(Player $player): bool {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getId() === ItemIds::ELYTRA) {
            if (!$item instanceof Durable) {
                SendMessage::Send($player, "持っているアイテムは修繕することが出来ません", "Repair", false);
                return false;
            }
            if ($item->getDamage() <= 0) {
                SendMessage::Send($player, "耐久力が減っていない為、修繕することができません", "Repair", false);
                return false;
            }
        }
        if (!$item instanceof TieredTool) {
            SendMessage::Send($player, "持っているアイテムは修繕することが出来ません", "Repair", false);
            return false;
        }
        if ($item->getDamage() <= 0) {
            SendMessage::Send($player, "耐久力が減っていない為、修繕することができません", "Repair", false);
            return false;
        }
        if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            SendMessage::Send($player, "衝撃エンチャントが付与されている為、修繕することが出来ません", "Repair", false);
            return false;
        }
        if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                SendMessage::Send($player, "ネザライトマイニングツールのみ修繕が可能です", "Repair", false);
                return false;
            }
        }
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                SendMessage::Send($player, "ネザライトマイニングツールのみ修繕が可能です", "Repair", false);
                return false;
            }
        }
        $itemIds = $item->getId();
        if ($itemIds >= 1000) {
            SendMessage::Send($player, "このアイテムは修繕することが出来ません", "Repair", false);
            return false;
        }
        return true;
    }
}
