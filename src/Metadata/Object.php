<?php
namespace Fmizzell\Systemizer\Metadata;
use Fmizzell\Systemizer\Metadata\Property;

class Object {
    private $name;
    private $properties;

    public function __construct($name) {
        $this->setName($name);
        $this->properties = array();
    }

    private function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function addProperty(Property $property) {
        $this->properties[] = $property;
    }

    public function getProperties() {
        return $this->properties;
    }

    /**
     * Create ObjectType metadata from an array.
     *
     * @param string $array
     *   An array.
     *
     * @return ObjectType
     *   A metadata ObjectType object.
     *
     * @throws Exception
     */
    public static function createFromArray($array) {

        if (array_key_exists('Name', $array)) {
            $object = new Object($array['Name']);
        }
        else {
            throw new Exception("The array did not contain a the necessary name key for the class.");
        }

        if(array_key_exists('Properties', $array)) {
            foreach ($array['Properties'] as $property) {
                if(array_key_exists("Name", $property) && strcmp($property["Type"], "Collection") == 0) {
                    $property = Collection::createFromArray($property);
                }
                else {
                    $property = Property::createFromArray($property);
                }

                $object->addProperty($property);
            }
        }

        return $object;
    }
} 