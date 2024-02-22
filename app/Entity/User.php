<?php
namespace Entity;

use Utils\Cache;
use ReflectionException;
use Models\User\User as ModelUser;

class User extends Entity
{
    public int $id;
    public bool $isActive;
    public bool $isBlocked = false;
    public int $groupId = 4;
    public string $group = 'Розничный покупатель';
    public string $name;
    public ?string $secondName;
    public string $lastName;
    public string $email;
    public string $phone;
    public string $password;
    public bool $hasPersonalDataAgreement = true;
    public bool $hasMailingAgreement = false; // подписание на рассылку
    public int $mailingTypeId = 2; // тип рассылки html
    public string $mailingType = 'html'; // тип рассылки
    public int $priceTypeId = 2; // розничная
    public string $priceType = 'Розничная'; // тип цены, по которой покупает
    public array $priceTypes = []; // разрешенные для просмотра типы цен
    public int $genderId = 1;
    public string $gender;
    public int $timezone = 0;
    public \DateTime $created;
    public ?\DateTime $updated = null;

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'id'                      => ['type' => 'int', 'field' => 'id'],
            'active'                  => ['type' => 'bool', 'field' => 'isActive'],
            'blocked'                 => ['type' => 'bool', 'field' => 'isBlocked'],
            'group_id'                => ['type' => 'int', 'field' => 'groupId'],
            'group_name'              => ['type' => 'string', 'field' => 'group'],
            'name'                    => ['type' => 'string', 'field' => 'name'],
            'second_name'             => ['type' => 'string', 'field' => 'secondName'],
            'last_name'               => ['type' => 'gender', 'field' => 'lastName'],
            'email'                   => ['type' => 'string', 'field' => 'email'],
            'phone'                   => ['type' => 'string', 'field' => 'phone'],
            'password'                => ['type' => 'string', 'field' => 'password'],
            'personal_data_agreement' => ['type' => 'bool', 'field' => 'hasPersonalDataAgreement'],
            'mailing'                 => ['type' => 'bool', 'field' => 'hasMailingAgreement'],
            'mailing_type_id'         => ['type' => 'int', 'field' => 'mailingTypeId'],
            'mailing_type'            => ['type' => 'string', 'field' => 'mailingType'],
            'price_type_id'           => ['type' => 'int', 'field' => 'priceTypeId'],
            'price_type'              => ['type' => 'string', 'field' => 'priceType'],
            'price_types'             => ['type' => 'array', 'field' => 'priceTypes'],
            'gender_id'               => ['type' => 'int', 'field' => 'genderId'],
            'gender'                  => ['type' => 'string', 'field' => 'gender'],
            'timezone'                => ['type' => 'int', 'field' => 'timezone'],
            'created'                 => ['type' => 'datetime', 'field' => 'created'],
            'updated'                 => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    /**
     * Сохранение пользователя в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelUser())->init($this)->save();
    }

    /**
     * Возвращает текущего пользователя
     * @return User
     */
    public static function getCurrent()
    {
        return (new self())->init(Cache::getUser() ?: ModelUser::getById(2));
    }
}
