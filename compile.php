<?php

// this file is used to compile the library into a single file
$compiledCode = '';

// iterate over all files in the src folder
$srcFolder = __DIR__ . '/src';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcFolder));

// append all files to the compiled code
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $compiledCode .= file_get_contents($file->getPathname()) . PHP_EOL;
    }
}

// write the compiled code to the dist folder
$distFolder = __DIR__ . '/dist';

if (!file_exists($distFolder)) {
    mkdir($distFolder);
}

file_put_contents($distFolder . '/optiGov.php', $compiledCode);