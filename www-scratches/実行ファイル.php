<?php

declare(strict_types = 1);

class Main {

	private int $member;

	public function __construct(?int $member = null){
		if($member === null) return;
		self::$member = $member;
	}

	public function getMember() : int{
		return self::$member;
	}
}

$main = new Main(10);
var_dump($main->getMember()); //10
$main = new Main(null);

var_dump($main->getMember()); //10