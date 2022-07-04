<?php

namespace deceitya\miningtools\normal;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;

class BuyForm extends CustomForm {

    private string $mode;
    private int $cost;
    private string $selection;
    private array $diamond;
    private array $netherite;

    public function __construct(Player $player, string $mode, int $cost, string $selection) {
        $this->mode = $mode;
        $this->cost = $cost;
        $this->selection = $selection;
        $this->diamond = Main::getInstance()->dataAcquisition("diamond");
        $this->netherite = Main::getInstance()->dataAcquisition("netherite");
        $diamondCost = ConfirmForm::DIAMOND_COST;
        $netheriteCost = ConfirmForm::NETHERITE_COST;
        switch ($mode) {
            case 'diamond':
                $explanation = new Label("DiamondMiningTools\nType : {$selection}\n\n必要金額 : {$diamondCost}\n\n付与エンチャント\nシルクタッチ Lv.1\n衝撃 Lv.1\n耐久 Lv.5\n\n注意事項\nネザライトツールと比べ耐久が少なく修繕不可\n範囲拡張等の拡張構成機能が使えない");
                break;
            case 'netherite':
                $explanation = new Label("NetheriteMiningTools\nType : {$selection}\n\n必要金額 : {$netheriteCost}\n\n付与エンチャント\nシルクタッチ Lv.1\n耐久 Lv.10");
                break;
            default:
                Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                return;
        }
        $this
            ->setTitle("Mining Tools")
            ->addElements($explanation);
    }

    public function handleSubmit(Player $player): void {
        if (EconomyAPI::getInstance()->myMoney($player) <= $this->cost) {
            $player->sendMessage(Main::PrefixRed . '所持金が足りない為処理が中断されました');
            return;
        }
        $item = $this->itemRegister();
        if (empty($item)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        if (!$player->getInventory()->canAddItem($item)) {
            $player->sendMessage(Main::PrefixRed . 'インベントリに空きが無い為処理が中断されました');
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $this->cost);
        $player->getInventory()->addItem($item);
        switch ($this->mode) {
            case "diamond":
                $player->sendMessage(Main::PrefixGreen . 'DiamondMiningToolsを購入しました');
                break;
            case "netherite":
                Server::getInstance()->broadcastMessage(Main::PrefixYellow . $player->getName() . 'がNetheriteMiningToolsを購入しました');
                break;
        }
    }

    public function itemRegister(): Item {
        $item = null;
        if ($this->mode === "diamond") {
            switch ($this->selection) {
                case "しゃべる":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SHOVEL);
                    $item->setCustomName($this->diamond["shovel"]["name"]);
                    break;
                case "つるはし":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_PICKAXE);
                    $item->setCustomName($this->diamond["pickaxe"]["name"]);
                    break;
                case "おの":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE);
                    $item->setCustomName($this->diamond["axe"]["name"]);
                    break;
            }
            $item->setLore([$this->diamond['description']]);
            $nbt = $item->getNamedTag();
            $nbt->setInt('MiningTools_3', 1);
            $item->setNamedTag($nbt);
            foreach ($this->diamond['enchant'] as $enchant) {
                $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
            }
        }
        if ($this->mode === "netherite") {
            switch ($this->selection) {
                case "しゃべる":
                    $item = ItemFactory::getInstance()->get(Main::NETHERITE_SHOVEL);
                    $item->setCustomName($this->netherite["shovel"]["name"]);
                    break;
                case "つるはし":
                    $item = ItemFactory::getInstance()->get(Main::NETHERITE_PICKAXE);
                    $item->setCustomName($this->netherite["pickaxe"]["name"]);
                    break;
                case "おの":
                    $item = ItemFactory::getInstance()->get(Main::NETHERITE_AXE);
                    $item->setCustomName($this->netherite["axe"]["name"]);
                    break;
            }
            $item->setLore([$this->netherite['description']]);
            $nbt = $item->getNamedTag();
            $nbt->setInt('MiningTools_3', 1);
            $item->setNamedTag($nbt);
            foreach ($this->netherite['enchant'] as $enchant) {
                $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
            }
        }
        return $item;
    }

}