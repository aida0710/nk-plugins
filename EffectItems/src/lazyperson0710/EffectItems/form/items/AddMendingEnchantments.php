<?php

namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\calculation\CheckItem;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class AddMendingEnchantments extends CustomForm {

    private Toggle $enable;

    //debug デバックしてください
    public function __construct(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $this->enable = new Toggle("修繕エンチャントを付与しますか？", false);
        $this
            ->setTitle("Item Edit")
            ->addElements(
                new Label("修繕エンチャントを付与するには、修繕付与アイテムを所持している必要があります"),
                $this->enable,
                new Label("現在所持しているアイテムの情報"),
                new Label("Vanillaアイテム名: " . $item->getVanillaName() . "\nCustomアイテム名: " . $item->getCustomName()),
            );
    }

    public function handleSubmit(Player $player): void {
        $inHandItem = $player->getInventory()->getItemInHand();
        if (!$this->enable->getValue()) {
            $player->sendMessage("§bItemEdit §7>> §a機能の有効化のボタンをオンにしていない為処理を中断しました");
            return;
        }
        if (!$inHandItem instanceof Durable) {
            $player->sendMessage("§bItemEdit §7>> §c道具や装備以外のアイテムは修繕エンチャントを付与することが出来ません");
            return;
        }
        if ((new CheckItem())->isMiningTools($inHandItem)) {
            $player->sendMessage("§bItemEdit §7>> §cMiningToolsに修繕を付与することはできません");
            return;
        }
        $approval = false;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getNamedTag()->getTag('AddMendingEnchantmentItem') !== null) {
                $player->getInventory()->removeItem($item->setCount(1));
                $approval = true;
                break;
            }
        }
        if ($approval === false) {
            $player->sendMessage("§bItemEdit §7>> §c修繕付与アイテムを取得することができませんでした");
            return;
        }
        $inHandItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING()), 1);
        $player->getInventory()->setItemInHand($inHandItem);
        $player->sendMessage("§bItemEdit §7>> §a修繕を付与しました");
    }

}