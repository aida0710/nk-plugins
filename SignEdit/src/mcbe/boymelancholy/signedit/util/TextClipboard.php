<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\util;

use pocketmine\block\utils\SignText;
use pocketmine\player\Player;

class TextClipboard {

    /** @var SignText[] */
    private array $textClipboards;
    private Player $player;

    public function __construct(Player $player) {
        $this->textClipboards = [];
        $this->player = $player;
    }

    public function getOwner(): string {
        return $this->player->getName();
    }

    public function add(SignText $text): bool {
        foreach ($this->textClipboards as $signText) {
            if ($signText->getLines() === $text->getLines()) {
                return false;
            }
        }
        $this->textClipboards[] = $text;
        sort($this->textClipboards);
        return true;
    }

    public function remove(int $index): ?SignText {
        if ($this->get($index) !== null) {
            $text = $this->textClipboards[$index];
            unset($this->textClipboards[$index]);
            sort($this->textClipboards);
            return $text;
        }
        return null;
    }

    public function get(int $index): ?SignText {
        return $this->textClipboards[$index] ?? null;
    }

    public function size(): int {
        return count($this->textClipboards);
    }

    /**
     * @return SignText[]
     */
    public function getAll(): array {
        return $this->textClipboards ?? [];
    }

    /** @var TextClipboard[] */
    public static array $clipboards = [];

    public static function getClipBoard(Player $player): TextClipboard {
        foreach (self::$clipboards as $cb) {
            if ($cb->getOwner() === $player->getName()) {
                return $cb;
            }
        }
        $newCb = new TextClipboard($player);
        self::$clipboards[] = $newCb;
        return $newCb;
    }

    public static function deleteClipboard(Player $player) {
        for ($i = 0; $i < count(self::$clipboards); ++$i) {
            if (self::$clipboards[$i]->getOwner() === $player->getName()) {
                unset(self::$clipboards[$i]);
            }
        }
    }
}