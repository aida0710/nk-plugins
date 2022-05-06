<?php

declare(strict_types=1);
namespace space\yurisi\Form\Sell;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

class SellRegisterForm implements Form {

    private Player $player;

    private string $text;

    private array $list = [];

    public function __construct(Player $player, string $text = "") {
        $this->player = $player;
        $this->text = $text;
    }

    public function handleResponse(Player $player, $data): void {
        if (!$data) return;
        if (!$this->list[$data[1]] instanceof Item) {
            $player->sendForm(new self($player, "§cエラーが発生しました"));
            return;
        }
        if (!is_numeric($data[2])) {
            $player->sendForm(new self($player, "§c数量は整数で入力してください"));
            return;
        }
        if ($data[2] <= 0) {
            $player->sendForm(new self($player, "§c数量は自然数で入力してください"));
            return;
        }
        if (!is_numeric($data[3])) {
            $player->sendForm(new self($player, "§c販売価格は整数で入力してください"));
            return;
        }
        if ($data[3] <= 0) {
            $player->sendForm(new self($player, "§c販売価格は自然数で入力してください"));
            return;
        }
        if ($data[3] > 5000000) {
            $player->sendForm(new self($player, "§c販売価格は500万円が上限です"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 218) {//シェルカーボックス
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 205) {//シェルカーボックス
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 387) {//署名された本
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 386) {//署名されてない本
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 397) {//頭アイテム
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 345) {//コンパス
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 357) {//cookie
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        if ($this->list[$data[1]]->getId() === 339) {//紙
            $player->sendForm(new self($player, "§cこのアイテムは出品できません"));
            return;
        }
        $amount = floor((int)$data[2]);
        $price = floor((int)$data[3]);
        $count = 0;
        foreach ($player->getInventory()->getContents() as $item) {
            if ($this->list[$data[1]]->getId() == $item->getId()) {
                if ($this->list[$data[1]]->getMeta() == $item->getMeta()) {
                    $count += $item->getCount();
                }
            }
        }
        if ($count < $amount) {
            $player->sendForm(new self($player, "§cインベントリーに入ってるアイテムの量が指定された量よりも少ないため出品できません"));
            return;
        }
        $cls = new YamlConfig();
        $all = $cls->getAll();
        $count = 0;
        foreach ($all as $name) {
            if (stristr($name['player'], $player->getName()) !== false) {
                $count++;
            }
        }
        if ($count >= 3) {
            $player->sendForm(new self($player, "§c現在3つ出品しているため新規に出品することは出来ません"));
            return;
        }
        $this->list[$data[1]]->setCount((int)$amount);
        $item = Item::nbtDeserialize(($this->list[$data[1]]->nbtSerialize()));
        $player->getInventory()->removeItem($item);
        $cls = new YamlConfig();
        $all = $cls->getAll();
        $cls->registerItem((int)$price, $player, $data[4], $this->list[$data[1]]->nbtSerialize());
        foreach ($all as $id) {
            $LastId = $id["id"];
        }
        $player->sendMessage("§bMarket §7>> §a{$item->getName()}§r§aを{$data[2]}個、{$data[3]}円で出品しました。MarketID:{$LastId}");
    }

    public function jsonSerialize() {
        $content[] = [
            "type" => "label",
            "text" => $this->text
        ];
        $content[] = [
            "type" => "dropdown",
            "text" => "売るアイテムを選択してください"
        ];
        $content[] = [
            "type" => "input",
            "text" => "個数を入力してください"
        ];
        $content[] = [
            "type" => "input",
            "text" => "販売価格を入力してください"
        ];
        $content[] = [
            "type" => "toggle",
            "text" => "プライベートで出品するか否か\nデフォルトでは公開されます"
        ];
        foreach ($this->player->getInventory()->getContents() as $item) {
            $content[1]["options"][] = $item->getName() . "§r";
            $this->list[] = $item;
        }
        return [
            'type' => 'custom_form',
            'title' => 'Free Market',
            'content' => $content
        ];
    }

}