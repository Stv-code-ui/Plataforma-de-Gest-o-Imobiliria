<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/autoload.php';

$relativeFile = 'tests' . DIRECTORY_SEPARATOR . 'utility.txt';
FileUtility::write($relativeFile, 'store');

if (FileUtility::read($relativeFile) !== 'store') {
	throw new RuntimeException('O store padrao nao foi utilizado');
}

FileUtility::delete($relativeFile);
SessionUtility::flush();

AuthUtility::login(['id' => 7, 'email' => 'test@example.com']);

if (!AuthUtility::check() || AuthUtility::id() !== 7) {
	throw new RuntimeException('A sessao de autenticacao falhou');
}
if (!AuthUtility::verifyPassword('secret', AuthUtility::hashPassword('secret'))) {
	throw new RuntimeException('O utilitario de password falhou');
}

AuthUtility::logout();

if (!AuthUtility::guest()) {
	throw new RuntimeException('O logout falhou');
}

$_COOKIE['utility_test'] = 'value';
if (!CookieUtility::has('utility_test') || CookieUtility::get('utility_test') !== 'value') {
	throw new RuntimeException('O utilitario de cookies falhou');
}
CookieUtility::forget('utility_test');

echo "UtilityTest: PASS\n";