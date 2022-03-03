<?php

namespace bbo51dog\bboform\element;

class Dropdown extends CustomFormElement {

    /** @var string */
    private $text;

    /** @var string[] */
    private $options;

    /**
     * Dropdown constructor.
     *
     * @param string $text
     * @param string[] $options
     */
    public function __construct(string $text, array $options) {
        $this->text = $text;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array {
        return [
            "type" => self::TYPE_DROPDOWN,
            "text" => $this->text,
            "options" => $this->options
        ];
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getValue(): int {
        return parent::getValue();
    }

    /**
     * @return string
     */
    public function getSelectedOption(): string {
        return $this->options[$this->getValue()];
    }
}