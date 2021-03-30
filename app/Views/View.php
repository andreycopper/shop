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
    use Magic;
    use Iterator;
    use Count;
    use ArrayAccess;

    /**
     * Возвращает строку - HTML-код шаблона
     * @param string $template
     * @param string $ext
     * @return false|string|null
     */
    public function render(string $template, string $ext = 'php')
    {
        $tmpl = defined('TEMPLATE') ? TEMPLATE : 'main';
        $file =
            TEMPLATES . DIRECTORY_SEPARATOR .
            $tmpl .
            (mb_substr($template, 0, 1) === '/' || mb_substr($template, 0, 1) === '\\' ? '' : DIRECTORY_SEPARATOR) .
            $template . '.' .
            $ext;

        if (empty($template) || !is_file($file)) return false;

        ob_start();
        foreach ($this as $name => $value) {
            $$name = $value;
        }
        include $file;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Отображает HTML-код шаблона
     * @param string $file
     * @param string $ext
     */
    public function display(string $file, string $ext = 'php')
    {
        $this->view = $this->render($file);
        echo $this->render('template');
    }
}
