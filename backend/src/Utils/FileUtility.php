<?php

declare(strict_types=1);

final class FileUtility
{
	public static function storePath(string $path = ''): string
	{
		$store = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'store';

		if ($path === '') {
			return $store;
		}

		return self::isAbsolutePath($path)
			? $path
			: $store . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
	}

	public static function exists(string $path): bool
	{
		return file_exists(self::storePath($path));
	}

	public static function isFile(string $path): bool
	{
		return is_file(self::storePath($path));
	}

	public static function isDirectory(string $path): bool
	{
		return is_dir(self::storePath($path));
	}

	public static function ensureDirectory(string $path, int $permissions = 0755): void
	{
		$path = self::storePath($path);

		if (is_dir($path)) {
			return;
		}

		if (!mkdir($path, $permissions, true) && !is_dir($path)) {
			throw new RuntimeException(sprintf('Nao foi possivel criar o diretorio [%s]', $path));
		}
	}

	public static function read(string $path): string
	{
		$path = self::storePath($path);

		if (!is_file($path)) {
			throw new RuntimeException(sprintf('O ficheiro [%s] nao existe', $path));
		}

		$content = file_get_contents($path);
		if ($content === false) {
			throw new RuntimeException(sprintf('Nao foi possivel ler o ficheiro [%s]', $path));
		}

		return $content;
	}

	public static function write(string $path, string $content, int $flags = 0): void
	{
		$path = self::storePath($path);
		self::ensureDirectory(dirname($path));

		if (file_put_contents($path, $content, $flags) === false) {
			throw new RuntimeException(sprintf('Nao foi possivel escrever o ficheiro [%s]', $path));
		}
	}

	public static function append(string $path, string $content): void
	{
		self::write($path, $content, FILE_APPEND | LOCK_EX);
	}

	public static function copy(string $source, string $destination): void
	{
		$source = self::storePath($source);
		$destination = self::storePath($destination);

		if (!is_file($source)) {
			throw new RuntimeException(sprintf('O ficheiro de origem [%s] nao existe', $source));
		}

		self::ensureDirectory(dirname($destination));

		if (!copy($source, $destination)) {
			throw new RuntimeException(sprintf('Nao foi possivel copiar [%s]', $source));
		}
	}

	public static function move(string $source, string $destination): void
	{
		$source = self::storePath($source);
		$destination = self::storePath($destination);

		if (!file_exists($source)) {
			throw new RuntimeException(sprintf('O caminho de origem [%s] nao existe', $source));
		}

		self::ensureDirectory(dirname($destination));

		if (!rename($source, $destination)) {
			throw new RuntimeException(sprintf('Nao foi possivel mover [%s]', $source));
		}
	}

	public static function delete(string $path): void
	{
		$path = self::storePath($path);

		if (is_file($path) || is_link($path)) {
			if (!unlink($path)) {
				throw new RuntimeException(sprintf('Nao foi possivel eliminar [%s]', $path));
			}

			return;
		}

		if (is_dir($path)) {
			foreach (new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS) as $item) {
				self::delete($item->getPathname());
			}

			if (!rmdir($path)) {
				throw new RuntimeException(sprintf('Nao foi possivel eliminar o diretorio [%s]', $path));
			}
		}
	}

	public static function list(string $directory = '', ?string $extension = null): array
	{
		$directory = self::storePath($directory);

		if (!is_dir($directory)) {
			throw new RuntimeException(sprintf('O diretorio [%s] nao existe', $directory));
		}

		$files = [];
		$normalizedExtension = $extension === null ? null : ltrim(strtolower($extension), '.');

		foreach (new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS) as $item) {
			if (!$item->isFile()) {
				continue;
			}

			if ($normalizedExtension !== null && strtolower($item->getExtension()) !== $normalizedExtension) {
				continue;
			}

			$files[] = $item->getPathname();
		}

		sort($files);
		return $files;
	}

	private static function isAbsolutePath(string $path): bool
	{
		return str_starts_with($path, '/')
			|| str_starts_with($path, '\\')
			|| preg_match('/^[A-Za-z]:[\\\\\/]/', $path) === 1;
	}
}
