<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 10/12/14
 * Time: 7:23 PM
 */

namespace Fmizzell\Systemizer\PrimitiveTypes;
use Valitron\Validator;


class PrimitiveType {
    protected $variable;
    protected $constraints;

    public function __construct($variable) {
        $validator = new Validator(array('var' => $variable));
        $validator->rule('regex', 'var', '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/');

        if(is_string($variable) && $validator->validate()) {
            $this->variable = $variable;
        }
        else {
            throw new \Exception(implode(" ", $validator->errors()));
        }
    }

    public function setConstraint($constraint) {
        $this->constraints[] = $constraint;
    }

    public function getConstraintLines() {
        if(empty($this->constraints)) { return array(); }

        $lines[] = "\$validator = new Validator(array('var' => \${$this->variable}));";

        foreach($this->constraints as $key => $constraint) {
            foreach($constraint as $key => $value) {
                $lines[] = "\$validator->rule('{$key}', 'var', '{$value}');";
            }
        }
        return $lines;
    }

    public function getValidationLine() {
        if(!empty($this->constraints)) {
            return " && \$validator->validate()";
        }
        return "";
    }
} 