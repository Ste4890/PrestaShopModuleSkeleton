<?php

namespace StefanoPelagotti\Module;

use \PrestashopLogger;

/**
 * Retrieves, updates and saves  module settings
 * created in Prestashop backoffice
 */
class Settings implements \JsonSerializable {

    public const CONFIG_FORM_NAME = 'config_form';


    //fixme only use const key everywhere
    public const KEY = 'PA_CUSTOMB2B';
    private static $cached_instance = null;

    public static function getInstance($key = false, bool $refresh = false): self {
        if (!self::$cached_instance || $refresh) {
            self::$cached_instance = new self($key);
        }

        return self::$cached_instance;
    }

    private function __construct($key = false) {

        $db_key = $key ?: self::KEY;
        $config = json_decode(\Configuration::get($db_key), true);

        if ($config) {
            $this->setFromArray($config);
        }

    }


    public function get($key) {
        return $this->$key;
    }

    public function getAll(): array {
        $arr = [];
        foreach ($this as $key => $value) {
            if ('db_key' !== $key) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public function getAllForForm(): array {
        $tmp = [];
        foreach ($this->getAll() as $key => $value) {

            $tmp[self::CONFIG_FORM_NAME . '[' . $key . ']'] = $value;
        }
        return $tmp;
    }

    public function set($key, $value): Settings {
        #todo sanitize value?
        $this->$key = $value;
        return $this;
    }

    public function setFromArray(array $data): Settings {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    public function save(): Settings {
        \Configuration::updateValue(self::KEY, json_encode($this));
        return $this;
    }

    public function jsonSerialize(): array {
        return $this->getAll();
    }

    public static function install(): bool {
        try {
            $s = new self();
            $s->save();
            return true;

        } catch (\Throwable $e) {
            PrestashopLogger::addLog("Prestart: cannot initialize conf save {$e->getMessage()}", 2);
            return false;
        }
    }

    public static function uninstall($key = false): bool {
        $db_key = $key ?: self::KEY;
        return \Configuration::deleteByName($db_key);
    }

}
