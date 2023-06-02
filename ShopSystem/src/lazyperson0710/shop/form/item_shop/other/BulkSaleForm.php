<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\other;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\shop\database\ItemShopAPI;
use pocketmine\player\Player;
use function var_dump;
use const PHP_EOL;

class BulkSaleForm extends CustomForm {

    /** @var Toggle[] */
    private array $toggles;

    public function __construct(Player $player, int $shopNumber, string $category) {
        var_dump('bulkSaleForm');
        $shopItems = ItemShopAPI::getInstance()->getCategoryItems($shopNumber, $category);
        $this
            ->setTitle('Item Shop')
            ->addElement(
                new Label(
                    '仮想ストレージとインベントリ含む全ての場所から一括で売却出来ます' . PHP_EOL .
                    '売却したいアイテムのtoggleを有効にして送信を押してください'
                ),
            );
        foreach ($shopItems as $item) {
            $toggleTemp = new Toggle($item->getDisplayName());
            $this->toggles[] = $toggleTemp;
            $this->addElement($toggleTemp);
        }
    }

    public function handleSubmit(Player $player) : void {
        var_dump($this->toggles);
    }

}
