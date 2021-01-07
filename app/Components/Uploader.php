<?php

namespace App\Components;

use App\Logger;
use App\Exceptions\UploadException;

/**
 * Class Uploader
 * @package App\Components
 */
class Uploader
{
    protected $formName;                  // Имя формы

    protected $file;                      // Данные о файле из массива $_FILES

    protected $filename;                  // Имя нового файла

    public $name;                         // Полное имя нового файла

    public $preview;                      // Полное имя нового превью

    public $path;                         // Путь для загрузки

    public $destination;                  // Директория для загрузки

    protected static $allowedType = [     // Разрешенные типы файлов
        'image/jpeg'  => 'jpg',
        'image/pjpeg' => 'jpeg',
        'image/png'   => 'png',
        'image/x-png' => 'png',
        'image/gif'   => 'gif'
    ];

    protected static $errors = [         // Виды ошибок
        1 => 'Размер принятого файла превысил максимально допустимый размер',
        2 => 'Размер загружаемого файла превысил значение',
        3 => 'Загружаемый файл был получен только частично',
        4 => 'Файл не был загружен',
        6 => 'Отсутствует временная папка',
        7 => 'Не удалось записать файл на диск',
        8 => 'Расширение остановило загрузку файла'
    ];

    public function __construct($formName, $directory)
    {
        $this->formName = $formName;
        $this->file     = $_FILES[$this->formName];
        $this->filename = $this->getFileName();
        $this->name     = $this->filename . '.' . $this->getExtension();
        $this->preview  = $this->getPreviewName() . '.' . $this->getExtension();

        if ('projects' === $directory) {
            $this->destination = $directory . '/' . $_POST['id'] . '/';
        } else {
            $this->destination = $directory . '/';
        }
    }

    public function path($path)
    {
        $this->path = $path;
        return $this;
    }

    protected function getExtension(): string
    {
        return self::$allowedType[$this->file['type']];
    }

    protected function getFileName(): string
    {
        return bin2hex(random_bytes(10));
    }

    protected function getPreviewName(): string
    {
        return $this->filename . '_thumb';
    }

    protected function checkDir()
    {
        if (empty($this->path)) {
            $exc = new UploadException('Путь не задан');
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        if (!is_dir($this->path . $this->destination)) {
            if (false === mkdir($this->path . $this->destination, 0755)) {
                $exc = new UploadException(sprintf('Не удалось создать директорию %s', $this->destination));
                Logger::getInstance()->error($exc);
                throw $exc;
            }
        }
        return true;
    }

    protected function checkFile()
    {
        if ('' === $this->file['name'] || '' === $this->file['type'] || 0 === $this->file['size']) {
            $exc = new UploadException('Не передан файл для загрузки');
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        if (0 !== $this->file['error']) {
            $exc = new UploadException(self::$errors[$this->file['error']]);
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        if (!array_key_exists($this->file['type'], self::$allowedType)) {
            $exc = new UploadException(sprintf('Файлы с типом %s не разрешенны к загрузке.', $this->file['type']));
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        $type = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if (!in_array($type, self::$allowedType, true)) {
            $exc = new UploadException(sprintf('Файлы с расширением %s не разрешенны к загрузке.', $type));
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        if (5242880 < $this->file['size']) {
            $exc = new UploadException('Слишком большой размер файла.');
            Logger::getInstance()->error($exc);
            throw $exc;
        }
        return true;
    }

    public function upload(): bool
    {
        if (true === $this->checkDir() && true === $this->checkFile()) {
            if (false === move_uploaded_file($this->file['tmp_name'], $this->path . $this->destination . $this->name)) {
                $exc = new UploadException('Невозможно перенести файл.');
                Logger::getInstance()->error($exc);
                throw $exc;
            }
            return true;
        } else {
            return false;
        }
    }
}
