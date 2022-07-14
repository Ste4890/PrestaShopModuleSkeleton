<?php

namespace StefanoPelagotti\Module\Constraints;

abstract class Locations {
    public const TWIG_ADMIN_TEMPLATES = '@Modules/modulename/views/templates/twig/admin/';
    public const MODULE_FOLDER = _PS_MODULE_DIR_ . 'modulename/';
    public const MODULE_TEMP_FOLDER = self::MODULE_FOLDER . 'temp/';
    //fixme: make use of prestashop native cache when appropriate
    public const CACHE_FOLDER = self::MODULE_TEMP_FOLDER . 'cache/';
    public const IMAGE_FOLDER = self::MODULE_TEMP_FOLDER . 'img/';
}