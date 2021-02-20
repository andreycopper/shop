<?php

namespace Models;

use System\Logger;
use System\Mailer;
use Exceptions\DbException;

/**
 * Class Event
 * @package App\Models
 */
class Event extends Model
{
    protected static $table = 'events';

    // тип рассылки
    const TYPE_MAIL = 1;
    const TYPE_SMS = 2;
    const TYPE_PUSH = 3;

    // номер шаблона
    const TEMPLATE_REGISTRATION = 1;
    const TEMPLATE_REGISTRATION_CONFIRM = 2;
    const TEMPLATE_RESTORE = 3;

    /**
     * Создает пользовательское событие
     * @param int $user_id - id пользователя
     * @param int $template_id - id шаблона
     * @param int $type - тип события
     * @param array $params - массив подстановок в шаблон
     * @return bool|int
     * @throws DbException
     */
    public static function create(int $user_id, int $template_id, int $type, array $params = [])
    {
        $user = User::getFullInfoById($user_id);
        $template = EventTemplate::getById($template_id, true);

        if (!empty($user->id)) { // пользователь найден
            if (!empty($template->id)) { // шаблон найден
                $event = new self();
                $event->user_id = $user->id;
                $event->event_type_id = $type;
                $event->event_template_id = $template->id;
                $event->created = date("Y-m-d H:i:s");

                if (self::TYPE_MAIL === $type) { // email-рассылка
                    $mail = (new Mailer($user, $template, $params))->send();
                    $event->result = $mail ? 1 : null;
                }

                if (self::TYPE_SMS === $type) { // sms-рассылка
                }

                if (self::TYPE_PUSH === $type) { // push-рассылка
                }

                if (false === $event->save()) {
                    Logger::getInstance()->error(
                        new DbException(
                            'Ошибка при сохранении события пользователя id = ' . $user_id .
                            ', шаблон id = ' . $template_id . ', тип события = ' . $type));
                    return false;
                }

                return true;
            }
            else { // шаблон не найден
                Logger::getInstance()->error(new DbException('При создании события не найден шаблон с id = ' . $template_id));
                return false;
            }
        }
        else { // пользователь не найден
            Logger::getInstance()->error(new DbException('При создании события не найден пользователь с id = ' . $user_id));
            return false;
        }
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_user_id($value)
    {
        return (int)$value;
    }

    public function filter_event_type_id($value)
    {
        return (int)$value;
    }

    public function filter_event_template_id($value)
    {
        return (int)$value;
    }

    public function filter_result($value)
    {
        return (int)$value;
    }
}
