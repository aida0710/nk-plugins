<?php

declare(strict_types = 1);
namespace bbo51dog\pmdiscord\element;

class Embed {

	private array $data = [];

	/**
	 * Get data
	 */
	public function getData() : array {
		return $this->data;
	}

	/**
	 * Set embed title
	 */
	public function setTitle(string $title) : self {
		$this->data['title'] = $title;
		return $this;
	}

	/**
	 * Set embed description
	 */
	public function setDesc(string $desc) : self {
		$this->data['description'] = $desc;
		return $this;
	}

	/**
	 * Set title url
	 */
	public function setUrl(string $url) : self {
		$this->data['url'] = $url;
		return $this;
	}

	/**
	 * Set time stamp
	 */
	public function setTime(string $time) : self {
		$this->data['timestamp'] = $time;
		return $this;
	}

	/**
	 * Set embed color
	 * $color is a color code which is decimal
	 */
	public function setColor(int $color) : self {
		$this->data['color'] = $color;
		return $this;
	}

	/**
	 * Set image url
	 */
	public function setImage(string $url) : self {
		$this->data['image']['url'] = $url;
		return $this;
	}

	/**
	 * Set thumbnail url
	 */
	public function setThumbnail(string $url) : self {
		$this->data['thumbnail']['url'] = $url;
		return $this;
	}

	/**
	 * Set text in the footer
	 */
	public function setFooterText(string $text) : self {
		$this->data['footer']['text'] = $text;
		return $this;
	}

	/**
	 * Set footer icon url
	 */
	public function setFooterIcon(string $url) : self {
		$this->data['footer']['icon_url'] = $url;
		return $this;
	}

	/**
	 * Set embed authors name
	 */
	public function setAuthorName(string $name) : self {
		$this->data['author']['name'] = $name;
		return $this;
	}

	/**
	 * Set embed authors url
	 */
	public function setAuthorUrl(string $url) : self {
		$this->data['author']['url'] = $url;
		return $this;
	}

	/**
	 * Set embed authors icon url
	 */
	public function setAuthorIcon(string $url) : self {
		$this->data['author']['icon_url'] = $url;
		return $this;
	}

	/**
	 * Add field
	 */
	public function addField(string $name, string $value, bool $inline = false) : self {
		$field['name'] = $name;
		$field['value'] = $value;
		if ($inline) {
			$field['inline'] = true;
		}
		$this->data['fields'][] = $field;
		return $this;
	}
}
