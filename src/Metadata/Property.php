<?php
namespace Fmizzell\Systemizer\Metadata;
use \Exception;

class Property {

    const REQUIRED = 0;
    const OPTIONAL = 1;
    const INTERNAL = 2;
    const ACCESSIBLE = 3;

    private $name;
    private $type;
    private $constraints;

    protected $kind;

    public function __construct($name) {
        $this->setName($name);
        $this->constraints = array();

        // Make all properties required by default.
        $this->kind = Property::REQUIRED;
    }

    private function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setKind($kind) {
       if($kind === Property::REQUIRED || $kind === Property::OPTIONAL || $kind === Property::INTERNAL || $kind === Property::ACCESSIBLE) {
           $this->kind = $kind;
       }
       else {
           throw new Exception("The kind should be one of the constants: REQUIRED, OPTIONAL, ACCESSIBLE or INTERNAL");
       }
    }

    public function getKind() {
        return $this->kind;
    }

    public function addConstraint($constraint) {
        $this->constraints[] = $constraint;
    }

    public function getConstraints() {
        return $this->constraints;
    }

    public static function createFromArray($array) {
        if (array_key_exists('Name', $array)) {
            $property = new Property($array['Name']);
        }
        else {
            throw new Exception("The array did not contain the necessary name key for the Property.");
        }

        // If no type is given, we will assume the most general type: String.
        if (array_key_exists('Type', $array)) {
            $property->setType($array['Type']);
        }
        else {
            $property->setType("String");
        }

        if (array_key_exists('Constraints', $array)) {
            foreach ($array['Constraints'] as $c) {
                $property->addConstraint($c);
            }
        }

        if (array_key_exists('Kind', $array)) {
            $kind = $array['Kind'];
            if($kind == "Required") {
                $property->setKind(Property::REQUIRED);
            }
            else if($kind == "Optional") {
                $property->setKind(Property::OPTIONAL);
            }
            else if($kind == "Internal") {
                $property->setKind(Property::INTERNAL);
            }
            else if($kind == "Accessible") {
                $property->setKind(Property::ACCESSIBLE);
            }
        }


        return $property;
    }
}