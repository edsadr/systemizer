<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fmizzell\Systemizer\Metadata;

/**
 * Description of Action
 *
 * @author jay
 */
class Action
{
    private $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    private function setName($name)
    {
        // @todo name should be an upper camelcase string.
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public static function createFromArray($array)
    {
        if (!empty($array['Name'])) {
            return new Action($array['Name']);
        }

        return;
    }
}
