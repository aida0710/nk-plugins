<?php

namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\Gacha\Calculation\ItemRegister;
use lazyperson0710\Gacha\Calculation\RankCalculation;
use lazyperson0710\Gacha\database\GachaItemAPI;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class GachaForm extends CustomForm {

    private Input $quantity;
    private string $categoryName;
    private array $probability;
    private array $cost;

    public function __construct(Player $player, string $categoryName) {
        $this->categoryName = $categoryName;
        $this->probability = GachaItemAPI::getInstance()->rankProbability[$categoryName][0];
        $this->cost = GachaItemAPI::getInstance()->categoryCost[$categoryName][0];
        $moneyApi = EconomyAPI::getInstance();
        $ticket = TicketAPI::getInstance();
        $this->quantity = new Input("ガチャを回したい回数を入力してください", "0");
        $this
            ->setTitle("Gacha System / {$categoryName}")
            ->addElements(
                new Label("ガチャコスト / 所持数量\nMoney -> {$this->cost["moneyCost"]} / {$moneyApi->myMoney($player->getName())}円\nMiningTicket -> {$this->cost["ticketCost"]}枚 / {$ticket->checkData($player)}枚"),
                new Label("ガチャの排出確率\nCommon -> {$this->probability["C"]}％\nUnCommon -> {$this->probability["UC"]}％\nRare -> {$this->probability["R"]}％\nSuperRare -> {$this->probability["SR"]}％\nSpecialSuperRare -> {$this->probability["SSR"]}％\nLegendary -> {$this->probability["L"]}％"),
                new Label("inventoryが満タンの場合アイテムはドロップする為アイテム削除には十分にお気を付けください"),
                $this->quantity,
            );
    }

    public function handleSubmit(Player $player): void {
        if ($this->quantity->getValue() == "") {
            SendMessage::Send($player, "回数を入力してください", "Gacha", false);
            return;
        }
        if (!preg_match('/^[0-9]+$/', $this->quantity->getValue())) {
            SendMessage::Send($player, "整数のみで入力してください", "Gacha", false);
            return;
        }
        if (!Server::getInstance()->isOp($player->getName())) {
            if ($this->quantity->getValue() >= 301) {
                SendMessage::Send($player, "301回以上は連続で回すことはできません", "Gacha", false);
                return;
            }
        }
        $result = (new RankCalculation($this->categoryName))->run($this->quantity->getValue(), $player);
        if (is_null($result)) return;
        $result = substr($result, 0, -1);
        $result = explode(",", $result);
        $formMessage = null;
        $onDrop = false;
        foreach ($result as $rank) {
            $item = (new ItemRegister($this->categoryName, $rank))->Items($rank);
            switch ($rank) {
                case "SR":
                    SendNoSoundMessage::Send($player, "SuperRare > {$item->getCustomName()}§r§eを{$this->probability[$rank]}％で当てました", "§bGacha", true);
                    break;
                case "SSR":
                    SendMessage::Send($player, "SpecialSuperRare > {$item->getCustomName()}§r§eを{$this->probability[$rank]}％で当てました", "§bGacha", true, "item.trident.thunder");
                    break;
                case "L":
                    SoundPacket::Send($player, "mob.enderdragon.death");
                    SendBroadcastMessage::Send("Legendary > {$item->getCustomName()}§r§eを{$player->getName()}が{$this->probability[$rank]}％で当てました", "Gacha");
                    break;
            }
            $formDisplayRank = match ($rank) {
                "C" => "§7Common§r",
                "UC" => "§aUnCommon§r",
                "R" => "§bRare§r",
                "SR" => "§dSuperRare§r",
                "SSR" => "§6SpecialSuperRare§r",
                "L" => "§cLegendary§r",
            };
            $formMessage .= "{$formDisplayRank} > {$item->getCustomName()}§rを{$this->probability[$rank]}％で当てました\n";
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                $player->dropItem($item);
                $onDrop = true;
            }
            if (mt_rand(1, 1000000) === 15000) {
                SendBroadcastMessage::Send("{$player->getName()}が0.001％の確率を当てました！Ticketを500枚プレゼントされました！", "Gacha");
                TicketAPI::getInstance()->addTicket($player, 500);
            }
        }
        SendForm::Send($player, (new ResultForm($formMessage, $onDrop)));
    }

}