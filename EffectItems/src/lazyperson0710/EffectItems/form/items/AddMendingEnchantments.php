<?php

namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\calculation\CheckItem;
use lazyperson710\core\packet\SendMessage\SendMessage;
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
            SendMessage::Send($player, "機能の有効化のボタンをオンにしていない為処理を中断しました", "ItemEdit", false);
            return;
        }
        if (!$inHandItem instanceof Durable) {
            SendMessage::Send($player, "道具や装備以外のアイテムは修繕エンチャントを付与することが出来ません", "ItemEdit", false);
            return;
        }
        if ((new CheckItem())->isMiningTools($inHandItem)) {
            SendMessage::Send($player, "MiningToolsに修繕を付与することはできません", "ItemEdit", false);
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
            SendMessage::Send($player, "修繕付与アイテムを取得することができませんでした", "ItemEdit", false);
            return;
        }
        $inHandItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING()), 1);
        $player->getInventory()->setItemInHand($inHandItem);
        SendMessage::Send($player, "修繕を付与しました", "ItemEdit", true);
    }

}