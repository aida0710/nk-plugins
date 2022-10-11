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
            ->setTitle("Bazaar System")
            ->addElements(
                $this->dropdown,
            );
    }

    public function handleSubmit(Player $player): void {
        $selectFilter = match ($this->dropdown->getSelectedOption()) {
            "ブロック" => "blocks",
            "ツール" => "tools",
            "エンチャント済みアイテム" => "enchanted",
            "食べ物" => "food",
            "装備" => "armor",
            "ポーション" => "potions",
            default => throw new \Error("不正な値が代入されました" . $this->dropdown->getSelectedOption()),
        };
        Server::getInstance()->dispatchCommand($player, "bazaar category {$selectFilter}");
    }
}