<?php

namespace StefanoPelagotti\Module;

use Db;
use Module;

use StefanoPelagotti\Module\Settings;


class Installer {
    protected $module;

    public function __construct(Module $module) {
        $this->module = $module;
    }

    public function install(): bool {
        $result = true;

        $tables = $this->getTablesToCreate();
        foreach ($tables as $table) {
            $created = Db::getInstance()->execute($table);
            if (!$created) {
                $result = false;
            }
        };

        if (!$this->registerHooks() ||
            !Settings::install()) {
            $result = false;
        }


        return $result;
    }

    public function uninstall($delete_stuff = false): bool {
        $result = true;

        if ($delete_stuff) {
            if (!$this->deleteTables() ||
                !$this->unregisterHooks() ||
                !Settings::uninstall()) {
                $result = false;
            }
        }

        return $result;
    }


    protected function getTablesToCreate(): array {
        return [
            RandomClass::getSQLTableDefinition(),
        ];
    }

    protected function getTablesToDelete():array {
        return [
            RandomClass::$definition['table'],
        ];
    }

    protected function deleteTables():bool {
        $result = true;

        $tables = $this->getTablesToDelete();
        foreach ($tables as $table) {
            $created = Db::getInstance()->execute('DROP TABLE IF EXISTS`'. _DB_PREFIX_.$table.'`;');
            if (!$created) {
                $result = false;
            }
        };


        return $result;
    }


    protected function registerHooks(): bool {
        $result = true;

        foreach ($this->module->hooks as $hook) {
            if (!$this->module->registerHook($hook)) {
                return false;
            }

        }
        return $result;
    }

    protected function unregisterHooks(): bool {
        $result = true;

        foreach ($this->module->hooks as $hook) {
            if (!$this->module->unregisterHook($hook)) {
                return false;
            }

        }
        return $result;
    }
}