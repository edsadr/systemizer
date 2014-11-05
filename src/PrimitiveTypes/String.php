<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 10/12/14
 * Time: 7:09 PM
 */

namespace Fmizzell\Systemizer\PrimitiveTypes;
use Fmizzell\Systemizer\PrimitiveTypes\PrimitiveType;


class String extends PrimitiveType {
    public function getValidationLine() {
        return "is_string(\${$this->variable})" . parent::getValidationLine();
    }
}