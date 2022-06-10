<?php
/*
 * await-generator
 *
 * Copyright (C) 2018 SOFe
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);
namespace shock95x\auctionhouse\libs\SOFe\AwaitGenerator;

use Exception;

/**
 * The default exception to throw into an async iterator
 * when `Traverser::interrupt()` is called.
 */
final class InterruptException extends Exception {

    private static $instance;

    public static function get(): self {
        self::$instance = self::$instance ?? new self;
        return self::$instance;
    }

    private function __construct() {
    }
}
