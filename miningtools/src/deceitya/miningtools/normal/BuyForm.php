<?php

namespace deceitya\miningtools\normal;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;

class BuyForm extends CustomForm {

    private string $mode;
    private int $cost;
    private string $selection;

    public const NETHERITE_SHOVEL = 744;
    public const NETHERITE_PICKAXE = 745;
    public const NETHERITE_AXE = 746;

    public function __construct(Player $player, string $mode, int $cost, string $selection) {
        $this->mode = $mode;
        $this->cost = $cost;
        $this->selection = $selection;
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
            //足りてない場合
        }
        $item = $this->itemRegister();
        if (!$player->getInventory()->canAddItem($item)) {
            $player->sendMessage('§bMiningTool §7>> §cインベントリに空きがありません。');
        }
        EconomyAPI::getInstance()->reduceMoney($player, $this->cost);
        $player->getInventory()->addItem($item);
        $player->sendMessage('§bMiningTool §7>> §aDiamondMiningToolsを購入しました。');
    }

    public function itemRegister(): Item {
        if ($this->mode === "diamond") {
            switch ($this->selection) {
                case "つるはし":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_PICKAXE);
                    break;
                case "しゃべる":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SHOVEL);
                    break;
                case "おの":
                    $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE);
                    break;
            }
        }
        if ($this->mode === "netherite") {
            switch ($this->selection) {
                case "つるはし":
                    $item = ItemFactory::getInstance()->get(self::NETHERITE_PICKAXE);
                    break;
                case "しゃべる":
                    $item = ItemFactory::getInstance()->get(self::NETHERITE_SHOVEL);
                    break;
                case "おの":
                    $item = ItemFactory::getInstance()->get(self::NETHERITE_AXE);
                    break;
            }
        }
        return $item;
    }

}