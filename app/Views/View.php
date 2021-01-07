<?php

namespace App\Views;

use App\Traits\Count;
use App\Traits\Magic;
use App\Traits\Iterator;
use App\Traits\ArrayAccess;

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
     * @return false|string|null
     */
    public function render(string $template)
    {
        if (empty($template)) return null;

        ob_start();
        foreach ($this as $name => $value) {
            $$name = $value;
        }
        include $template;
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
        $body =
            TEMPLATES . DIRECTORY_SEPARATOR .
            TEMPLATE .
            (mb_substr($file, 0, 1) === '/' || mb_substr($file, 0, 1) === '\\' ? '' : DIRECTORY_SEPARATOR) .
            $file . '.' .
            $ext;

        $template = TEMPLATES . '/' . TEMPLATE . '/template.php';

        if (is_file($body)) $this->view = $this->render($body);

        echo $this->render(is_file($template) ? $template : null);
    }
}
