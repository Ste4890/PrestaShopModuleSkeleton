<?php

namespace StefanoPelagotti\Module\Interfaces;

interface SelfInstalling {
    public static function getSQLTableDefinition(): string;
    public static function getForeignKeysDefinition(): string;
    public static function getColumnsDefinition():string;
}