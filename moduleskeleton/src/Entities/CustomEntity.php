<?php

namespace StefanoPelagotti\Module\Entities;

use StefanoPelagotti\Module\Entities\SelfInstallingObjectModel;



class CustomEntity extends  SelfInstallingObjectModel {


    /**
     * for now, only numeric and string type are supported by self installing
     * @var array
     */
    public static $definition = [
        'table' => 'name_of_table_wo_prefix',
        'primary' => 'id_name_of_table_wo_prefix',
        'fields' => [
            'numeric_col' => [
                'type' => self::TYPE_INT,
                'shop' => 'both',
                'validate' => 'isUnsignedInt',
                'required' => true,
            ],
            'string_col' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => false,
                'size' => 1024,
            ],
        ],
    ];



}