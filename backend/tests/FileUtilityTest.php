<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/autoload.php';

$directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'gestao-imobiliaria-file-test';
$file = $directory . DIRECTORY_SEPARATOR . 'example.txt';
$copy = $directory . DIRECTORY_SEPARATOR . 'copy.txt';

FileUtility::delete($directory);
FileUtility::write($file, 'primeira linha');
FileUtility::append($file, PHP_EOL . 'segunda linha');
FileUtility::copy($file, $copy);

if (FileUtility::read($copy) !== 'primeira linha' . PHP_EOL . 'segunda linha') {
	throw new RuntimeException('A leitura ou escrita do ficheiro falhou');
}
if (count(FileUtility::list($directory, 'txt')) !== 2) {
	throw new RuntimeException('A listagem de ficheiros falhou');
}

FileUtility::delete($directory);

if (FileUtility::exists($directory)) {
	throw new RuntimeException('A eliminacao do diretorio falhou');
}

echo "FileUtilityTest: PASS\n";