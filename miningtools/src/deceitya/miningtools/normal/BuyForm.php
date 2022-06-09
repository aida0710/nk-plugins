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
        switch ($mode) {
            case 'diamond':
                $explanation = new Label("dummy text");
                break;
            case 'netherite':
                $explanation = new Label("dummy text2");
                break;
            default:
                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
        }
        $this
            ->setTitle("Mining Tools")
            ->addElements($explanation);
    }

    public function handleSubmit(Player $player): void {
        if (EconomyAPI::getInstance()->myMoney($player) <= $this->cost) {
            $player->sendMessage('§bMiningTools §7>> §c所持金が足りません');
            return;
        }
        $item = $this->itemRegister();
        if (empty($item)) {
            Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        if (!$player->getInventory()->canAddItem($item)) {
            $player->sendMessage('§bMiningTools §7>> §cインベントリに空きがありません');
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $this->cost);
        $player->getInventory()->addItem($item);
        $player->sendMessage('§bMiningTools §7>> §aMiningToolsを購入しました');
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