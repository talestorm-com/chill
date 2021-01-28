<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM;

/**
 * @property string $message
 * @property bool $changed
 */
class EventCollection implements \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    /** @var string */
    protected $message = null;

    /** @var bool */
    protected $changed = false;

    /** @var Event[] */
    protected $items;

    protected function __get__message() {
        return $this->message;
    }

    protected function __get__changed() {
        return $this->changed;
    }

    protected function __construct(string $message) {
        $this->message = trim(mb_strtoupper($message, "UTF-8"));
        $this->items = [];
    }

    public function add(string $class, string $method): EventCollection {
        return $this->add_event(Event::F($this->message, $class, $method));
    }

    public function exists(string $class, string $method): bool {
        $event_key = Event::mk_event_key($this->message, $class, $method);
        return array_key_exists($event_key, $this->items);
    }

    public function exists_event(Event $event) {
        return $this->exists($event->class, $event->method);
    }

    public function add_event(Event $event): EventCollection {
        if (!$this->exists_event($event)) {
            $this->items[$event->key] = $event;
            $this->changed = true;
        }
        return $this;
    }

    public function run(EventKVS $params = null) {
        foreach ($this->items as $item) {
            $item->run($params);
        }
    }

    public function __wakeup() {
        $this->changed = false;
    }

    /**
     * 
     * @param string $message
     * @return \GEM\EventCollection
     */
    public static function F(string $message): EventCollection {
        return new static($message);
    }

}
