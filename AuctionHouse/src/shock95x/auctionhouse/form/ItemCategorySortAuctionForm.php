<?php

namespace shock95x\auctionhouse\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class ItemCategorySortAuctionForm extends CustomForm {

    private Dropdown $dropdown;

    public function __construct() {
        $category = [
            "ブロック",
            "ツール",
            "エンチャント済みアイテム",
            "食べ物",
            "装備",
            "ポーション",
        ];
        $this->dropdown = new Dropdown("\n\n\nフィルターを掛けたいカテゴリーを選択して下さい", $category);
        $this
            ->setTitle("Auction System")
            ->addElements(
                $this->dropdown,
            );
    }

    public function handleSubmit(Player $player): void {
        switch ($this->dropdown->getSelectedOption()) {
            case "ブロック":
                $selectFilter = "blocks";
                break;
            case "ツール":
                $selectFilter = "tools";
                break;
            case "エンチャント済みアイテム":
                $selectFilter = "enchanted";
                break;
            case "食べ物":
                $selectFilter = "food";
                break;
            case "装備":
                $selectFilter = "armor";
                break;
            case "ポーション":
                $selectFilter = "potions";
                break;
            default:
                Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
        }
        Server::getInstance()->dispatchCommand($player, "ah category {$selectFilter}");
    }
}