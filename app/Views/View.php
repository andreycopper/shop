<?php

namespace Views;

use Traits\Count;
use Traits\Magic;
use Traits\Iterator;
use Traits\ArrayAccess;

/**
 * Class View
 * @package App\Views
 */
class View implements \Iterator, \Countable, \ArrayAccess
{
    public $view; // содержимое страницы для вывода в шаблоне

    use Magic;
    use Iterator;
    use Count;
    use ArrayAccess;

    /**
     * Возвращает строку - HTML-код шаблона
     * @param string $template - шаблон
     * @param array $vars - передаваемые в шаблон переменные
     * @return false|string|null
     */
    public function render(string $template, array $vars = [])
    {
        $fileinfo = pathinfo($template);
        $ext = !empty($fileinfo['extension']) ? ".{$fileinfo['extension']}" : '.php';

        $tmpl = defined('TEMPLATE') ? TEMPLATE : 'main';
        $file =
            _TEMPLATES . DIRECTORY_SEPARATOR .
            $tmpl .
            (mb_substr($template, 0, 1) === '/' || mb_substr($template, 0, 1) === '\\' ? '' : DIRECTORY_SEPARATOR) .
            $template . $ext;

        if (empty($template) || !is_file($file)) return false;

        ob_start();
        foreach ($this as $name => $value) $$name = $value;

        if (!empty($vars) && is_array($vars)) foreach ($vars as $key => $var) $$key = $var;

        include $file;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Отображает HTML-код шаблона
     * @param string $file - шаблон
     * @param array $vars - передаваемые в шаблон переменные
     */
    public function display(string $file, array $vars = [])
    {
        $this->view = $this->render($file, $vars);
        echo $this->render('template', $vars);
    }

    /**
     * Отображает HTML-код шаблона
     * @param string $file - шаблон
     * @param array $vars - передаваемые в шаблон переменные
     */
    public function display_element(string $file, array $vars = [])
    {
        echo $this->render($file, $vars);
    }
}
