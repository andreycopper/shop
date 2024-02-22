<?php
namespace Entity;

use ReflectionException;
use Models\Category as ModelCategory;
use Utils\Cache;

class Category extends Entity
{
    public int $id;
    public ?int $isActive = 1;
    public ?\DateTime $activeFrom = null;
    public ?\DateTime $activeTo = null;
    public ?int $parentId = null;
    public ?string $link = null;
    public string $name;
    public ?string $image = null;
    public ?string $description = null;
    public int $descriptionTypeId = 1;
    public string $descriptionType;
    public ?int $count = 0;
    public int $sort = 500;
    public \DateTime $created;
    public ?\DateTime $updated = null;
    public array $subCategories = [];

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'id'                  => ['type' => 'int', 'field' => 'id'],
            'active'              => ['type' => 'bool', 'field' => 'isActive'],
            'active_from'         => ['type' => 'datetime', 'field' => 'activeFrom'],
            'active_to'           => ['type' => 'datetime', 'field' => 'activeTo'],
            'parent_id'           => ['type' => 'int', 'field' => 'parentId'],
            'link'                => ['type' => 'string', 'field' => 'link'],
            'name'                => ['type' => 'string', 'field' => 'name'],
            'image'               => ['type' => 'string', 'field' => 'image'],
            'description'         => ['type' => 'string', 'field' => 'description'],
            'description_type_id' => ['type' => 'int', 'field' => 'descriptionTypeId'],
            'description_type'    => ['type' => 'string', 'field' => 'descriptionType'],
            'count'               => ['type' => 'int', 'field' => 'count'],
            'sort'                => ['type' => 'int', 'field' => 'sort'],
            'created'             => ['type' => 'datetime', 'field' => 'created'],
            'updated'             => ['type' => 'datetime', 'field' => 'updated'],
            'sub_categories'      => ['type' => 'array', 'field' => 'subCategories'],
        ];
    }

    /**
     * Сохранение пользователя в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelCategory())->init($this)->save();
    }

    public static function getByName(string $name)
    {
        return (new self())->init(ModelCategory::get($name));
    }
}
