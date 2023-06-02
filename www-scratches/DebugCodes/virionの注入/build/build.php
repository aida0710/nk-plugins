<?php

declare(strict_types = 1);
const NAME = 'CheeseEscape';
$file_phar = __DIR__ . DIRECTORY_SEPARATOR . NAME . '.phar';
$exclusions = [
    NAME . 'phar',
    '.idea',
    '.git',
    'README.md',
    'LICENSE',
    'vendor',
    'build.php',
    'composer.json',
    'composer.lock',
    'Stub.php',
    '.php-cs-fixer.cache',
    '.php-cs-fixer.dist.php',
    'phpstan.neon.dist',
    'virions',
    'composer.phar',
];
$files = [];
$dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
if (file_exists($file_phar)) {
    echo 'Phar file already exists, overwriting...';
    echo PHP_EOL;
    Phar::unlinkArchive($file_phar);
}
foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)) as $path => $file) {
    foreach ($exclusions as $exclusion) if (str_contains($path, $exclusion)) continue 2;
    echo 'added ' . $path . PHP_EOL;
    if ($file->isFile() === false) continue;
    $files[str_replace($dir, '', $path)] = $path;
}
echo 'Compressing...' . PHP_EOL;
$pluginPhar = new Phar($file_phar, 0);
$pluginPhar->startBuffering();
$pluginPhar->setSignatureAlgorithm(\Phar::SHA1);
$pluginPhar->buildFromIterator(new \ArrayIterator($files));
if (isset($argv[1]) && $argv[1] === 'enableCompressAll') {
    $pluginPhar->compressFiles(Phar::GZ);
} else {
    foreach ($pluginPhar as $file => $finfo) {
        /** @var PharFileInfo $finfo */
        if ($finfo->getSize() > (1024 * 512)) {
            $finfo->compress(\Phar::GZ);
        }
    }
}
$pluginPhar->setStub(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Stub.php'));
$pluginPhar->stopBuffering();
echo 'compiles completed.' . PHP_EOL;
foreach (getPharFilesFromDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'virions') as $dir) {
    $virion = new Phar($dir);
    virion_infect(
        pathinfo($virion->getPath(), PATHINFO_FILENAME),
        $virion,
        pathinfo($pluginPhar->getPath(), PATHINFO_FILENAME),
        $pluginPhar,
    );
}
echo 'end.' . PHP_EOL;
/**
 * @param string $directory
 * @return array<string>
 */
function getPharFilesFromDirectory(string $directory) : array {
    $files = [];
    foreach (new DirectoryIterator($directory) as $fileInfo) {
        if ($fileInfo->isFile() && $fileInfo->getExtension() === 'phar') {
            $files[] = $fileInfo->getPathname();
        }
    }
    return $files;
}

function virion_infect(string $virusName, Phar $virus, string $hostName, Phar $host) : bool {
    //$virus->startBuffering();
    $host->startBuffering();
    /* Check to make sure virion.yml exists in the virion */
    if (!isset($virus["virion.yml"])) {
        echo "virion.yml not found in $virusName" . PHP_EOL;
        return false;
    }
    $virusPath = "phar://" . str_replace(DIRECTORY_SEPARATOR, "/", $virus->getPath()) . "/";
    $virionYml = yaml_parse(file_get_contents($virusPath . "virion.yml"));
    if (!is_array($virionYml)) {
        echo "Corrupted virion.yml, could not activate virion $virusName" . PHP_EOL;
        return false;
    }
    /* Check to make sure plugin.yml exists in the plugin */
    if (!isset($host["plugin.yml"])) {
        echo "plugin.yml not found in $hostName" . PHP_EOL;
        return false;
    }
    $hostPath = "phar://" . str_replace(DIRECTORY_SEPARATOR, "/", $host->getPath()) . "/";
    $pluginYml = yaml_parse(file_get_contents($hostPath . "plugin.yml"));
    if (!is_array($pluginYml)) {
        echo "Corrupted plugin.yml found in plugin $hostName" . PHP_EOL;
        return false;
    }
    /* Infection Log. File that keeps all the virions injected into the plugin */
    $infectionLog = isset($host["virus-infections.json"]) ? json_decode(file_get_contents($hostPath . "virus-infections.json"), true) : [];
    /* Virion injection process now starts */
    $genus = $virionYml["name"];
    $antigen = $virionYml["antigen"];
    foreach ($infectionLog as $old) {
        if ($old["antigen"] === $antigen) {
            echo "Plugin $hostName is already infected with $virusName" . PHP_EOL;
            return false;
        }
    }
    $antibody = getPrefix($pluginYml) . $antigen;
    $infectionLog[$antibody] = $virionYml;
    echo "Using antibody $antibody for virion $genus ({$antigen})" . PHP_EOL;
    $hostPharPath = "phar://" . str_replace(DIRECTORY_SEPARATOR, "/", $host->getPath());
    $hostChanges = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($hostPharPath)) as $name => $chromosome) {
        if ($chromosome->isDir())
            continue;
        if ($chromosome->getExtension() !== "php")
            continue;
        $rel = cut_prefix($name, $hostPharPath);
        $data = change_dna(file_get_contents($name), $antigen, $antibody, $hostChanges);
        if ($data !== "")
            $host[$rel] = $data;
    }
    $restriction = "src/" . str_replace("\\", "/", $antigen) . "/";
    $ligase = "src/" . str_replace("\\", "/", $antibody) . "/";
    $viralChanges = 0;
    foreach (new RecursiveIteratorIterator($virus) as $name => $genome) {
        if ($genome->isDir())
            continue;
        $rel = cut_prefix($name, "phar://" . str_replace(DIRECTORY_SEPARATOR, "/", $virus->getPath()) . "/");
        if (str_starts_with($rel, "resources/")) {
            $host[$rel] = file_get_contents($name);
        } elseif (str_starts_with($rel, "src/")) {
            if (!str_starts_with($rel, $restriction)) {
                echo "Warning: File $rel in virion is not under the antigen $antigen ($restriction)";
                $newRel = $rel;
            } else {
                $newRel = $ligase . cut_prefix($rel, $restriction);
            }
            $data = change_dna(file_get_contents($name), $antigen, $antibody, $viralChanges);
            $host[$newRel] = $data;
        }
    }
    $host["virus-infections.json"] = json_encode($infectionLog);
    //$virus->stopBuffering();
    $host->stopBuffering();
    echo "Shaded $hostChanges references in $hostName and $viralChanges references in $virusName." . PHP_EOL;
    return true;
}

function getPrefix(array $pluginYml) : string {
    $main = $pluginYml["main"];
    $mainArray = explode("\\", $main);
    array_pop($mainArray);
    $path = implode("\\", $mainArray);
    return $path . "\\libs\\";
}

function cut_prefix(string $string, string $prefix) : string {
    if (!str_starts_with($string, $prefix))
        throw new AssertionError("\$string does not start with \$prefix:\n$string\n$prefix");
    return substr($string, strlen($prefix));
}

function change_dna(string $chromosome, string $antigen, string $antibody, &$count = 0) : string {
    $tokens = token_get_all($chromosome);
    $tokens[] = ""; // should not be valid though
    foreach ($tokens as $offset => $token) {
        if (!is_array($token) or $token[0] !== T_WHITESPACE) {
            /** @noinspection IssetArgumentExistenceInspection */
            [$id, $str, $line] = is_array($token) ? $token : [-1, $token, $line ?? 1];
            //namespace test; is a T_STRING whereas namespace test\test; is not.
            if (isset($init, $prefixToken) and $id === T_STRING) {
                if ($str === $antigen) { // case-sensitive!
                    $tokens[$offset][1] = $antibody . substr($str, strlen($antigen));
                    ++$count;
                } elseif (stripos($str, $antigen) === 0) {
                    echo "\x1b[38;5;227m\n[WARNING] Not replacing FQN $str case-insensitively.\n\x1b[m";
                }
                unset($init, $prefixToken);
            } else {
                if ($id === T_NAMESPACE) {
                    $init = $offset;
                    $prefixToken = $id;
                } elseif ($id === T_NAME_QUALIFIED) {
                    if (($str[strlen($antigen)] ?? "\\") === "\\") {
                        if (str_starts_with($str, $antigen)) { // case-sensitive!
                            $tokens[$offset][1] = $antibody . substr($str, strlen($antigen));
                            ++$count;
                        } elseif (stripos($str, $antigen) === 0) {
                            echo "\x1b[38;5;227m\n[WARNING] Not replacing FQN $str case-insensitively.\n\x1b[m";
                        }
                    }
                    unset($init, $prefixToken);
                } elseif ($id === T_NAME_FULLY_QUALIFIED) {
                    if (str_starts_with($str, "\\" . $antigen . "\\")) { // case-sensitive!
                        $tokens[$offset][1] = "\\" . $antibody . substr($str, strlen($antigen) + 1);
                        ++$count;
                    } elseif (stripos($str, "\\" . $antigen . "\\") === 0) {
                        echo "\x1b[38;5;227m\n[WARNING] Not replacing FQN $str case-insensitively.\n\x1b[m";
                    }
                    unset($init, $prefixToken);
                }
            }
        }
    }
    $ret = "";
    foreach ($tokens as $token) {
        $ret .= is_array($token) ? $token[1] : $token;
    }
    return $ret;
}
