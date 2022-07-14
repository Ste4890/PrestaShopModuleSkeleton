<?php

namespace StefanoPelagotti\Module\Entities;

use StefanoPelagotti\Module\Interfaces\SelfInstalling;

abstract class SelfInstallingObjectModel extends \ObjectModel implements SelfInstalling {


    public static function getSQLTableDefinition(): string {

        $pk = self::$definition['primary'];

        $head = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_;
        $table = self::$definition['table'] . '` (';
        $primary = "`$pk` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, ";

        $columns = self::getColumnsDefinition();
        $set_as_primary = "PRIMARY KEY ({$pk}) ";
        $foreign_keys = self::getForeignKeysDefinition();

        //fixme: make charset (and engine?) configurable by the final class itself
        $tail = ") ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";


        return $head . $table . $primary. $columns. $set_as_primary. $foreign_keys. $tail;
    }

    public static function getForeignKeysDefinition(): string {

        return "";
    }

    public static function getColumnsDefinition(): string {
        $cols = "";

        foreach (self::$definition['fields'] as $col_name => $col_data) {
            $tmp_def = "`$col_name` ";
            $type = $col_data['type'] === self::TYPE_INT ? "INT(10) UNSIGNED " : "VARCHAR(1024) ";

            $tmp_def .= $type;
            $tmp_def .= $col_data['required'] ? "NOT NULL, " : ", ";

            $cols .= $tmp_def;

        }

        return $cols;

    }
}