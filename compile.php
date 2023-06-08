<?php

// this file is used to compile the library into a single file
$compiledCode = '<?php ' . PHP_EOL;

// iterate over all files in the src folder
$srcFolder = __DIR__ . '/src';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcFolder));

// append all files to the compiled code
foreach ($iterator as $file) {
    if ($file->isFile()) {
        // get the code of the file
        $code = file_get_contents($file->getPathname()) . PHP_EOL;

        // remove the <?php tag from the code
        $code = str_replace('<?php', '', $code);

        // remove the closing tag from the code
        $code = str_replace('?>', '', $code);

        // append the code to the compiled code
        $compiledCode .= $code;
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
echo 'Size of compiled code: ' . round(strlen($compiledCode)/1000, 2) . ' kB' . PHP_EOL;

// return as success
return 0;