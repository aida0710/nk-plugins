<?php

namespace deceitya\CustomRecipe;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;

class CraftEvnetListener implements Listener {

    public function onCraft(CraftItemEvent $event) {
        $player = $event->getPlayer();
        foreach ($event->getOutputs() as $item) {
            switch ($item) {
                case VanillaBlocks::WATER()->asItem():
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = VanillaBlocks::WATER()->asItem();
                    $output->setCustomName("精製水");
                    $output->setLore([
                        "lore1" => "生活ワールドと農業ワールドで使用可能です",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;

                //case "採掘速度上昇バフアイテム":
                //    //case ItemFactory::getInstance()->get(383, 110, 1)://採掘速度上昇バフアイテム
                //    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                //    $table = $window->getContents();
                //    foreach ($table as $id => $craftitem) {
                //        $craftitem->setCount($craftitem->getCount() - 1);
                //        $window->setItem($id, $craftitem);
                //    }
                //    $event->cancel();
                //    $output = ItemFactory::getInstance()->get(383, 110, 1);
                //    $output->setCustomName("採掘速度上昇バフアイテム");
                //    $output->setLore([
                //        "lore1" => "ネザライトインゴットを作成するための材料",
                //        "lore2" => "タップすると3分間採掘速度上昇エフェクトが付与されます",
                //    ]);
                //    $player->getInventory()->addItem($output);
                //    break;
            }
        }
    }
}

