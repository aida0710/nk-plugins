<?php

namespace lazyperson710\sff\form\itemNameChange;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use Exception;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\item\ToolTier;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilBreakSound;
use pocketmine\world\sound\AnvilUseSound;

class ItemNameChangeMainForm extends CustomForm {

    private Durable $item;
    private int $level;
    private string $mode;

    public function __construct(int $level, Durable $item, string $mode) {
        $this->item = $item;
        $this->level = $level;
        $this->mode = $mode;
        $this
            ->setTitle("ItemNameChange")
            ->addElements(
                new Label("")
            );
    }

    public function handleSubmit(Player $player): void {
        $consumption = "error";
        try {
            if ($this->checkItem($player) === false) {
                return;
            }
        } catch (Exception $e) {
        }
        if ($this->mode === "command") {
            if (EconomyAPI::getInstance()->myMoney($player->getName()) < 3500) {
                $player->sendMessage("§bRepair §7>> §c手数料分を取得できませんでした。手数料3500円");
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
                $player->sendMessage("§bRepair §7>> §c修理費用を取得できませんでした");
                return;
            }
        } elseif ($player->getXpManager()->getXpLevel() < $this->level) {
            $player->sendMessage("§bRepair §7>> §cレベルが足りませんでした要求レベル->{$this->level}");
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
                $player->sendMessage('§bRepair §7>> §c1%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 80) {
            if (mt_rand(1, 100) <= 2) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c2%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 70) {
            if (mt_rand(1, 100) <= 3) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c3%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 60) {
            if (mt_rand(1, 100) <= 4) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c4%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 50) {
            if (mt_rand(1, 100) <= 5) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c5%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 40) {
            if (mt_rand(1, 100) <= 6) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c6%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 30) {
            if (mt_rand(1, 100) <= 7) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c7%の確率で修理に修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 20) {
            if (mt_rand(1, 100) <= 8) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c8%の確率で修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 10) {
            if (mt_rand(1, 100) <= 9) {
                $this->itemDisappearance($player);
                $player->sendMessage('§bRepair §7>> §c9%の確率で修理に失敗したためアイテムが消滅しました');
            } else {
                $this->itemRepair($player, $consumption);
            }
        } elseif (mt_rand(1, 100) <= 10) {
            $this->itemDisappearance($player);
            $player->sendMessage('§bRepair §7>> §c10%の確率で修理に失敗したためアイテムが消滅しました');
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
        $player->getWorld()->addSound($player->getPosition(), new AnvilUseSound());
        switch ($consumption) {
            case "ingot":
                $player->sendMessage("§bRepair §7>> §aインゴットを一個消費してアイテムを修理しました");
                break;
            case "level":
                $player->sendMessage("§bRepair §7>> §aLevelを{$this->level}消費してアイテムを修理しました");
                break;
            default:
                $player->sendMessage("§bRepair §7>> §c何かを消費してアイテムを修理しました");
                break;
        }
    }

    /**
     * @throws Exception
     */
    public function checkItem(Player $player): bool {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getId() === ItemIds::ELYTRA) {
            if (!($item instanceof Durable)) {
                throw new Exception();
            }
            if ($item->getDamage() <= 0) {
                $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
                return false;
            }
        }
        if (!$item instanceof TieredTool) {
            $player->sendMessage("§bRepair §7>> §c持っているアイテムは修繕することが出来ません");
            return false;
        }
        if ($item->getDamage() <= 0) {
            $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
            return false;
        }
        if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            $player->sendMessage('§bRepair §7>> §c衝撃エンチャントが付与されている為、修繕することが出来ません');
            return false;
        }
        if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                return false;
            }
        }
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            if ($item->getTier() === ToolTier::DIAMOND()) {
                $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                return false;
            }
        }
        $itemIds = $item->getId();
        if ($itemIds >= 1000) {
            $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕することが出来ません');
            return false;
        }
        return true;
    }
}
