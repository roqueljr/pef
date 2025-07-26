<?php

namespace App\Core\Models;

use Exception;

class View
{
    protected static string $viewsPath = './resources/views';
    protected static array $sections = [];
    protected static string $layout = '';

    protected static function csrfToken(): string
    {
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function setPath(string $path): void
    {
        self::$viewsPath = rtrim($path, '/');
    }

    protected static function bladePath(string $dot): string
    {
        return str_replace('.', '/', $dot);
    }

    public static function render(string $template, array $data = [])
    {
        self::$sections = [];
        self::$layout = '';

        $content = self::compile($template, $data);

        if (self::$layout) {
            echo self::compile(self::$layout, $data);
        } else {
            echo $content;
        }
    }

    protected static function compile(string $template, array $data)
    {
        $file = self::$viewsPath . "/$template.blade.php";
        if (!file_exists($file)) {
            View::render('pageNotFound');
            return;
        }

        extract($data);

        $raw = file_get_contents($file);

        // Layout support: @extends('layout')
        if (preg_match('/@extends\([\'"](.+)[\'"]\)/', $raw, $match)) {
            self::$layout = self::bladePath($match[1]);
            $raw = str_replace($match[0], '', $raw);
        }

        // Sections
        $raw = preg_replace_callback('/@section\([\'"](.+)[\'"]\)(.*?)@endsection/s', function ($m) {
            self::$sections[$m[1]] = $m[2];
            return '';
        }, $raw);

        // Yields
        $raw = preg_replace_callback('/@yield\([\'"](.+)[\'"]\)/', function ($m) {
            return self::$sections[$m[1]] ?? '';
        }, $raw);

        // Includes
        $raw = preg_replace_callback('/@include\([\'"](.+)[\'"]\)/', function ($m) use ($data) {
            $incPath = self::$viewsPath . '/' . self::bladePath($m[1]) . '.blade.php';
            if (file_exists($incPath)) {
                $included = file_get_contents($incPath);
                return self::compilePartial($included, $data);
            }
            return '';
        }, $raw);

        // Escaped output: {{ variable }}
        $raw = preg_replace_callback('/{{\s*(\w+)\s*}}/', function ($m) use ($data) {
            return htmlspecialchars($data[$m[1]] ?? '');
        }, $raw);

        // Handle @css('path/to/file.css')
        $raw = preg_replace_callback('/@css\([\'"](.+)[\'"]\)/', function ($m) {
            $path = htmlspecialchars($m[1], ENT_QUOTES);
            $fullPath = '/resources/libs/' . $path;
            return "<link rel=\"stylesheet\" href=\"{$fullPath}\">";
        }, $raw);

        // Handle @js('path/to/file.js')
        $raw = preg_replace_callback('/@js\([\'"](.+)[\'"]\)/', function ($m) {
            $path = htmlspecialchars($m[1], ENT_QUOTES);
            $fullPath = '/resources/libs/' . $path;
            return "<script src=\"{$fullPath}\"></script>";
        }, $raw);

        // Raw PHP expressions: {{{ expression }}}
        $raw = preg_replace_callback('/{{{\s*(.+?)\s*}}}/', function ($m) use ($data) {
            extract($data);
            ob_start();
            eval ('echo ' . $m[1] . ';');
            return ob_get_clean();
        }, $raw);

        // PHP expressions: {{ expression }}
        $raw = preg_replace_callback('/{{\s*(.+?)\s*}}/', function ($m) use ($data) {
            extract($data);
            ob_start();
            try {
                eval ('echo ' . $m[1] . ';');
            } catch (\Throwable $e) {
                echo "[View error: " . $e->getMessage() . "]";
            }
            return ob_get_clean();
        }, $raw);

        $raw = str_replace('@csrf', self::csrfToken(), $raw);

        // Control structures with proper PHP syntax
        $raw = preg_replace('/@if\s*\((.*?)\)/', '<?php if ($1): ?>', $raw);
        $raw = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif ($1): ?>', $raw);
        $raw = preg_replace('/@else\b/', '<?php else: ?>', $raw);
        $raw = preg_replace('/@endif\b/', '<?php endif; ?>', $raw);

        $raw = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach ($1): ?>', $raw);
        $raw = preg_replace('/@endforeach\b/', '<?php endforeach; ?>', $raw);

        $raw = preg_replace('/@for\s*\((.*?)\)/', '<?php for ($1): ?>', $raw);
        $raw = preg_replace('/@endfor\b/', '<?php endfor; ?>', $raw);

        $raw = preg_replace('/@while\s*\((.*?)\)/', '<?php while ($1): ?>', $raw);
        $raw = preg_replace('/@endwhile\b/', '<?php endwhile; ?>', $raw);

        // Raw PHP blocks
        $raw = str_replace('@php', '<?php', $raw);
        $raw = str_replace('@endphp', '?>', $raw);

        ob_start();
        $raw = "<?php use App\\Core\\Models\\Route; ?>" . $raw;
        eval ('?>' . $raw);
        return ob_get_clean();
    }

    protected static function compilePartial(string $raw, array $data): string
    {
        // Allow simple includes to use variables
        $raw = preg_replace_callback('/{{\s*(.+?)\s*}}/', function ($m) use ($data) {
            extract($data);
            ob_start();
            try {
                eval ('echo ' . $m[1] . ';');
            } catch (\Throwable $e) {
                echo "[Include error: " . $e->getMessage() . "]";
            }
            return ob_get_clean();
        }, $raw);

        return $raw;
    }
}