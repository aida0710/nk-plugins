<?php

declare(strict_types = 1);
const Test = [
	'a',
	'b',
	'c',
	'd',
	'e',
	'f',
	'g',
];
const Test2 = [
	'h',
	'i',
	'j',
	'k',
	'l',
	'm',
	'n',
];
const Test3 = [
	'o',
	'p',
	'q',
	'r',
	's',
	't',
	'u',
];
const All = [
	...Test,
	...Test2,
	...Test3,
];
var_dump(Test);
var_dump(All);
