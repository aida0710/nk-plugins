<?php

namespace lazyperson710\itemEdit\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\sff\form\PlayerForm;
use pocketmine\player\Player;
use pocketmine\Server;

class ItemInformationForm extends CustomForm {

    public function __construct(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $nameTag = $item->getNamedTag();
        foreach ($nameTag->getValue() as $tag) {
            var_dump($tag->toString());//nbtデータが取れると信じてる
        }
        $enchants[] = null;
        foreach ($item->getEnchantments() as $enchant){
            if(is_string($enchant->getType()->getName())){
                $enchants[] .= $enchant->getType()->getName();
            }else{
                $enchants[] .= Server::getInstance()->getLanguage()->translate($enchant->getType()->getName());
            }
            $enchantLevel = $enchant->getLevel();
        }
        $itemLore[] = null;
        foreach ($item->getLore() as $lore){
            $itemLore[] .= "{$lore}\n";
        }
        if (is_null($itemLore)){
            $itemLore = "このアイテムに説明はありません";
        }
        $this
            ->setTitle("Item Edit")
            ->addElements(
                //取得する情報はアイテムのid,meta
                //vanillaネーム、customネーム
                //enchant, level
                //tag
                //まぁそんな感じにとれるやつ片っ端から取りたい
                new Label("現在所持しているアイテムの情報"),
                new Label("ItemId {$item->getId()}/{$item->getMeta()}\nItemName\n{$item->getName()}\n{$item->getVanillaName()}\n{$item->getCustomName()}"),
                new Label("ItemLore\n{$itemLore}"),
                new Label("Enchant")
            );
    }
}