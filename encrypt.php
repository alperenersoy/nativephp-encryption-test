<?php

$iterator = new RecursiveDirectoryIterator(__DIR__);

foreach (new RecursiveIteratorIterator($iterator) as $file) {
    if (!$file->isFile()) {
        continue;
    }

    if ($file->getExtension() !== 'php') {
        continue;
    }

    if ($file->getPathname() === __FILE__) {
        continue;
    }

    if (str_contains($file->getPathname(), __DIR__ . '/vendor/')) {
        continue;
    }

    $file_content = trim(file_get_contents($file->getPathname()));

    if (str_starts_with($file_content, '<?php')) {
        $file_content = substr($file_content, strlen('<?php'));
    }

    if (str_ends_with($file_content, '?>')) {
        $file_content = substr($file_content, 0, -2);
    }

    $file_content = trim($file_content);
    
    $pathName = $file->getPathname();

    $encryped_path = "{$pathName}.enc";

    // encrypt original file and put it with .enc extension
    file_put_contents($encryped_path, nativephp_crypt($file_content));

    // prepare new content with eval or blade render
    $new_content = <<<PHP
        <?php return eval(nativephp_decrypt(file_get_contents('{$encryped_path}')));
        PHP;

    if (str_ends_with($pathName, '.blade.php')) {
        $new_content = <<<BLADE
        @php
        echo Blade::render(nativephp_decrypt(file_get_contents('{$encryped_path}')), \$__data ?? []);
        @endphp
        BLADE;
    }

    // replace original file with new content
    file_put_contents($pathName, $new_content);
}