<?php

namespace deceitya\CustomRecipe;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\Server;

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
                case ItemFactory::getInstance()->get(745, 0, 1)://つるはし
                    var_dump($item . "80");
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(745, 0, 1);
                    $output->setLore([
                        "lore" => "製作者 : {$event->getPlayer()->getName()}",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::MENDING(), 1);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    Server::getInstance()->broadcastMessage("§bLiberation §7>> §e{$event->getPlayer()->getName()}がNetherite Pickaxeを製作しました");
                    break;
                case ItemFactory::getInstance()->get(746, 0, 1)://おの
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(746, 0, 1);
                    $output->setLore([
                        "lore" => "製作者 : {$event->getPlayer()->getName()}",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::MENDING(), 1);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    Server::getInstance()->broadcastMessage("§bLiberation §7>> §e{$event->getPlayer()->getName()}がNetherite Axeを製作しました");
                    break;
                case ItemFactory::getInstance()->get(744, 0, 1)://シャベル
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(744, 0, 1);
                    $output->setLore([
                        "lore" => "製作者 : {$event->getPlayer()->getName()}",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::MENDING(), 1);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    Server::getInstance()->broadcastMessage("§bLiberation §7>> §e{$event->getPlayer()->getName()}がNetherite Shovelsを製作しました");
                    break;
                case ItemFactory::getInstance()->get(743, 0, 1)://けん
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(743, 0, 1);
                    $output->setLore([
                        "lore" => "製作者 : {$event->getPlayer()->getName()}",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::MENDING(), 1);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    Server::getInstance()->broadcastMessage("§bLiberation §7>> §e{$event->getPlayer()->getName()}がNetherite Swordを製作しました");
                    break;
                case ItemFactory::getInstance()->get(747, 0, 1)://くわ
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(747, 0, 1);
                    $output->setLore([
                        "lore" => "製作者 : {$event->getPlayer()->getName()}",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::MENDING(), 1);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    Server::getInstance()->broadcastMessage("§bLiberation §7>> §e{$event->getPlayer()->getName()}がNetherite Hoeを製作しました");
                    break;
                case ItemFactory::getInstance()->get(742):
                    var_dump($item . "166");
                    $event->cancel();
                    break;
            }
            switch ($item->getCustomName()) {
                case "金のハンドル":
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = VanillaItems::BLAZE_ROD()->setCustomName("金のハンドル");
                    $output->setLore([
                        "lore1" => "ネザライトのツールを作成するときに棒の代わりに使用できます",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 15);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    break;
                case "スクラップmark.1":
                    //case ItemFactory::getInstance()->get(383, 24, 1)://スクラップmark.1
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(383, 24, 1);
                    $output->setCustomName("スクラップmark.1");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                        "lore2" => "81倍圧縮スクラップ",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 81);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    break;
                case "スクラップmark.2":
                    //case ItemFactory::getInstance()->get(752, 729, 1)://スクラップmark.2
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(752, 729, 1);
                    $output->setCustomName("スクラップmark.2");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                        "lore2" => "729倍圧縮スクラップ",
                    ]);
                    $enchant = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 729);
                    $output->addEnchantment($enchant);
                    $player->getInventory()->addItem($output);
                    break;
                case "小さな惑星のモーメント":
                    //case ItemFactory::getInstance()->get(383, 26, 0):
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(383, 26, 1);
                    $output->setCustomName("小さな惑星のモーメント");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                case "歯車":
                    //case ItemFactory::getInstance()->get(383, 111, 0):
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(383, 111, 1);
                    $output->setCustomName("歯車");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                case "採掘速度上昇バフアイテム":
                    //case ItemFactory::getInstance()->get(383, 110, 1)://採掘速度上昇バフアイテム
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(383, 110, 1);
                    $output->setCustomName("採掘速度上昇バフアイテム");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                        "lore2" => "タップすると3分間採掘速度上昇エフェクトが付与されます",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                case "スパナ":
                    //case ItemFactory::getInstance()->get(383, 125, 1)://スパナ
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(383, 125, 1);
                    $output->setCustomName("スパナ");
                    $output->setLore([
                        "lore1" => "ネザライトインゴットを作成するための材料",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                case "不純物の多いネザライトインゴット":
                    //case ItemFactory::getInstance()->get(405, 15, 1)://不純物の多いネザライトインゴット
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = ItemFactory::getInstance()->get(405, 15, 1);
                    $output->setCustomName("不純物の多いネザライトインゴット");
                    $output->setLore([
                        "lore1" => "スクラップを使用したためとても不純物が多いネザライトインゴット",
                        "lore2" => "不純物を取り除くには焼く必要がありそうだ・・・",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                /*case "魔剤x16個セット":
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = VanillaItems::EXPERIENCE_BOTTLE()->setCount(16);
                    $output->setCustomName("魔剤x16個セット");
                    $output->setLore([
                        "lore1" => "魔剤が16個だから魔剤は16個なのです。",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;
                case "魔剤x4個セット":
                    $window = $player->getCurrentWindow() ?? $player->getCraftingGrid();
                    $table = $window->getContents();
                    foreach ($table as $id => $craftitem) {
                        $craftitem->setCount($craftitem->getCount() - 1);
                        $window->setItem($id, $craftitem);
                    }
                    $event->cancel();
                    $output = VanillaItems::EXPERIENCE_BOTTLE()->setCount(4);
                    $output->setCustomName("魔剤x4個セット");
                    $output->setLore([
                        "lore1" => "魔剤が4個だから魔剤は4個なのです。",
                    ]);
                    $player->getInventory()->addItem($output);
                    break;*/
            }
        }
    }
}

