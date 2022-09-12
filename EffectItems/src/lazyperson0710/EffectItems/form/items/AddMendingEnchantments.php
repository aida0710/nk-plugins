<?php

namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\calculation\CheckItem;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class AddMendingEnchantments extends CustomForm {

    public function __construct() {
        $this
            ->setTitle("Item Edit")
            ->addElements(
            //todo 確認画面
                new Label(""),
            );
    }

    public function handleSubmit(Player $player): void {
        //todo MiningToolsのチェック
        $inHandItem = $player->getInventory()->getItemInHand();
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
            $player->sendMessage("§bItemNameChange §7>> §cアイテム名変更アイテムを取得することができませんでした");
            return;
        }
        $inHandItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING()), 1);
        $player->getInventory()->setItemInHand($inHandItem);
        $player->sendMessage("§bItemEdit §7>> §a");
    }

}