<?php
namespace app\events\dispatcher;

/**
 * Диспетчер событий.
 * Управляет подписчиками (listeners) и уведомляет их при возникновении событий.
 */
class EventDispatcher
{
    /** @var array<string, callable[]> Список слушателей по типу события */
    private array $listeners = [];

    /**
     * Регистрирует слушателя для указанного события.
     *
     * @param string $eventClass
     * @param callable $listener
     * @return void
     */
    public function addListener(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    /**
     * Отправляет событие всем зарегистрированным слушателям.
     *
     * @param object $event
     * @return void
     */
    public function dispatch(object $event): void
    {
        $eventClass = get_class($event);
        if (!empty($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as $listener) {
                call_user_func($listener, $event);
            }
        }
    }
}