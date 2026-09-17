<?php

declare(strict_types=1);

final class View
{
	private static ?string $path = null;
	private static ?string $cachePath = null;
	private array $sections = [];

	public function __construct(?string $path = null, ?string $cachePath = null)
	{
		self::$path = rtrim($path ?? dirname(__DIR__, 2) . '/views', '/\\');
		self::$cachePath = rtrim($cachePath ?? dirname(__DIR__, 2) . '/storage/views', '/\\');
	}

	public static function make(string $name, array $data = []): string
	{
		return (new self())->render($name, $data);
	}

	public function render(string $name, array $data = []): string
	{
		$compiledFile = $this->compile($this->resolve($name));

		ob_start();
		try {
			extract($data, EXTR_SKIP);
			include $compiledFile;
			return (string) ob_get_clean();
		} catch (Throwable $exception) {
			ob_end_clean();
			throw $exception;
		}
	}

	public function section(string $name, string $content): void
	{
		$this->sections[$name] = $content;
	}

	public function yieldSection(string $name): string
	{
		return $this->sections[$name] ?? '';
	}

	public function escape(mixed $value): string
	{
		return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}

	private function resolve(string $name): string
	{
		$relativeName = str_replace(['\\', '.'], ['/', '/'], trim($name, '/')) . '.php';
		$root = realpath(self::$path ?? '');
		$file = realpath(($root ?: self::$path) . '/' . $relativeName);

		if ($root === false || $file === false || !str_starts_with($file, $root . DIRECTORY_SEPARATOR)) {
			throw new InvalidArgumentException(sprintf('A view [%s] nao foi encontrada', $name));
		}

		return $file;
	}

	private function compile(string $viewFile): string
	{
		$cachePath = self::$cachePath ?? dirname(__DIR__, 2) . '/storage/views';
		if (!is_dir($cachePath) && !mkdir($cachePath, 0775, true) && !is_dir($cachePath)) {
			throw new RuntimeException(sprintf('Nao foi possivel criar o cache de views em [%s]', $cachePath));
		}

		$compiledFile = $cachePath . '/' . sha1($viewFile) . '.php';
		if (!is_file($compiledFile) || filemtime($compiledFile) < filemtime($viewFile)) {
			$compiled = $this->compileContents((string) file_get_contents($viewFile));
			file_put_contents($compiledFile, $compiled, LOCK_EX);
		}

		return $compiledFile;
	}

	private function compileContents(string $contents): string
	{
		$parent = null;
		if (preg_match('/@extends\([\'\"]([^\'\"]+)[\'\"]\)/', $contents, $matches)) {
			$parent = $matches[1];
			$contents = str_replace($matches[0], '', $contents);
		}

		$contents = preg_replace_callback('/@section\([\'\"]([^\'\"]+)[\'\"]\)(.*?)@endsection/s', static function (array $matches): string {
			return '<?php ob_start(); ?>' . $matches[2] . '<?php $this->section(' . var_export($matches[1], true) . ', (string) ob_get_clean()); ?>';
		}, $contents) ?? $contents;
		$contents = preg_replace_callback('/@include\([\'\"]([^\'\"]+)[\'\"](?:,\s*(\[[^)]*\]))?\)/', static function (array $matches): string {
			return '<?php echo $this->render(' . var_export($matches[1], true) . ', ' . ($matches[2] ?? '[]') . '); ?>';
		}, $contents) ?? $contents;
		$contents = preg_replace('/\{\{\s*(.*?)\s*\}\}/s', '<?= $this->escape($1) ?>', $contents) ?? $contents;
		$contents = preg_replace('/\{!!\s*(.*?)\s*!!\}/s', '<?= $1 ?>', $contents) ?? $contents;
		$contents = preg_replace('/@yield\(([\'\"])([^\'\"]+)\1\)/', '<?= $this->yieldSection(\'${2}\') ?>', $contents) ?? $contents;
		$contents = preg_replace('/@if\s*\((.*?)\)/', '<?php if ($1): ?>', $contents) ?? $contents;
		$contents = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif ($1): ?>', $contents) ?? $contents;
		$contents = str_replace(['@else', '@endif'], ['<?php else: ?>', '<?php endif; ?>'], $contents);
		$contents = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach ($1): ?>', $contents) ?? $contents;
		$contents = str_replace('@endforeach', '<?php endforeach; ?>', $contents);

		if ($parent !== null) {
			$contents .= '<?php echo $this->render(' . var_export($parent, true) . ', get_defined_vars()); ?>';
		}

		return $contents;
	}
}