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

// print success message
echo 'Successfully compiled the library into a single file `dist/optiGov.php`.' . PHP_EOL;

// print the size of the compiled code
echo 'Size of compiled code: ' . strlen($compiledCode) . ' bytes' . PHP_EOL;

// return as success
return 0;