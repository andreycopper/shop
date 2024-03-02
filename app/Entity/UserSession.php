<?php
namespace Entity;

use DateTime;
use ReflectionException;
use Models\User\UserSession as ModelUserSession;

class UserSession extends Entity
{
    public int $id;
    public bool $isActive = false;
    public string $login;
    public int $userId = 2;
    public int $serviceId = 2;
    public string $ip;
    public string $device;
    public DateTime $logIn;
    public ?DateTime $expire;
    public ?string $token;
    public ?string $comment;

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'id'         => ['type' => 'int', 'field' => 'id'],
            'active'     => ['type' => 'bool', 'field' => 'isActive'],
            'login'      => ['type' => 'string', 'field' => 'login'],
            'user_id'    => ['type' => 'int', 'field' => 'userId'],
            'service_id' => ['type' => 'int', 'field' => 'serviceId'],
            'ip'         => ['type' => 'string', 'field' => 'ip'],
            'log_in'     => ['type' => 'datetime', 'field' => 'logIn'],
            'expire'     => ['type' => 'datetime', 'field' => 'expire'],
            'token'      => ['type' => 'string', 'field' => 'token'],
            'comment'    => ['type' => 'string', 'field' => 'comment']
        ];
    }

    /**
     * Сохранение пользователя в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelUserSession())->init($this)->save();
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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    /**
     * @param int $serviceId
     */
    public function setServiceId(int $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     */
    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    /**
     * @return DateTime
     */
    public function getLogIn(): DateTime
    {
        return $this->logIn;
    }

    /**
     * @param DateTime $logIn
     */
    public function setLogIn(DateTime $logIn): void
    {
        $this->logIn = $logIn;
    }

    /**
     * @return DateTime|null
     */
    public function getExpire(): ?DateTime
    {
        return $this->expire;
    }

    /**
     * @param DateTime|null $expire
     */
    public function setExpire(?DateTime $expire): void
    {
        $this->expire = $expire;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}
