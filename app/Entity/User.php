<?php
namespace Entity;

use DateTime;
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
    public DateTime $created;
    public ?DateTime $updated = null;

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

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = ModelUser::getById($params['id'], $params);
                break;
            case !empty($params['phone']):
                $user = ModelUser::getByPhone($params['phone'], $params);
                break;
            case !empty($params['email']):
                $user = ModelUser::getByEmail($params['email'], $params);
                break;
        }

        if (empty($user)) return null;
        $object = new self();
        $object->init($user);
        return $object;
    }

    /**
     * Возвращает текущего пользователя
     * @return User
     */
    public static function getCurrent()
    {
        return (new self())->init(Cache::getUser() ?: ModelUser::getById(2));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     */
    public function setIsBlocked(bool $isBlocked): void
    {
        $this->isBlocked = $isBlocked;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    /**
     * @param string|null $secondName
     */
    public function setSecondName(?string $secondName): void
    {
        $this->secondName = $secondName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isHasPersonalDataAgreement(): bool
    {
        return $this->hasPersonalDataAgreement;
    }

    /**
     * @param bool $hasPersonalDataAgreement
     */
    public function setHasPersonalDataAgreement(bool $hasPersonalDataAgreement): void
    {
        $this->hasPersonalDataAgreement = $hasPersonalDataAgreement;
    }

    /**
     * @return bool
     */
    public function isHasMailingAgreement(): bool
    {
        return $this->hasMailingAgreement;
    }

    /**
     * @param bool $hasMailingAgreement
     */
    public function setHasMailingAgreement(bool $hasMailingAgreement): void
    {
        $this->hasMailingAgreement = $hasMailingAgreement;
    }

    /**
     * @return int
     */
    public function getMailingTypeId(): int
    {
        return $this->mailingTypeId;
    }

    /**
     * @param int $mailingTypeId
     */
    public function setMailingTypeId(int $mailingTypeId): void
    {
        $this->mailingTypeId = $mailingTypeId;
    }

    /**
     * @return string
     */
    public function getMailingType(): string
    {
        return $this->mailingType;
    }

    /**
     * @param string $mailingType
     */
    public function setMailingType(string $mailingType): void
    {
        $this->mailingType = $mailingType;
    }

    /**
     * @return int
     */
    public function getPriceTypeId(): int
    {
        return $this->priceTypeId;
    }

    /**
     * @param int $priceTypeId
     */
    public function setPriceTypeId(int $priceTypeId): void
    {
        $this->priceTypeId = $priceTypeId;
    }

    /**
     * @return string
     */
    public function getPriceType(): string
    {
        return $this->priceType;
    }

    /**
     * @param string $priceType
     */
    public function setPriceType(string $priceType): void
    {
        $this->priceType = $priceType;
    }

    /**
     * @return array
     */
    public function getPriceTypes(): array
    {
        return $this->priceTypes;
    }

    /**
     * @param array $priceTypes
     */
    public function setPriceTypes(array $priceTypes): void
    {
        $this->priceTypes = $priceTypes;
    }

    /**
     * @return int
     */
    public function getGenderId(): int
    {
        return $this->genderId;
    }

    /**
     * @param int $genderId
     */
    public function setGenderId(int $genderId): void
    {
        $this->genderId = $genderId;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return int
     */
    public function getTimezone(): int
    {
        return $this->timezone;
    }

    /**
     * @param int $timezone
     */
    public function setTimezone(int $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * @param DateTime|null $updated
     */
    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }


}
