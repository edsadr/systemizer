<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 10/13/14
 * Time: 8:31 AM
 */

namespace Fmizzell\Systemizer\Metadata;


class Collection extends Property{
    private $dimensions;
    private $itemType;

    public function __construct($name) {
        parent::__construct($name);

        parent::setType("Collection");
        $this->dimensions = array();

        // We always assume the most general type for the elements
        // in a collection: String.
        $this->itemType = "String";

        $this->kind = \Fmizzell\Systemizer\Metadata\Property::OPTIONAL;
    }

    /**
     * The type is set automatically, so let's not do anything here.
     */
    public function setType($type) {
    }

    /**
     * No Collection can ever be required.
     */
    public function setRequired($bool) {
    }

    public function addDimension(Property $dimension) {
        $this->dimensions[] = $dimension;
    }

    public function getDimensions() {
        return $this->dimensions;
    }

    public function setItemType($itemType) {
        $this->itemType = $itemType;
    }

    public function getItemType() {
        return $this->itemType;
    }

    public static function createFromArray($array) {
        if (array_key_exists('Name', $array)) {
            $property = new Collection($array['Name']);
        }
        else {
            throw new Exception("The array did not contain the necessary name key for the Collection.");
        }

        if(array_key_exists('Dimensions', $array)) {
            foreach($array['Dimensions'] as $d) {
                $dimension = Property::createFromArray($d);
                $property->addDimension($dimension);
            }
        }

        if(array_key_exists('Items Type', $array)) {
            $property->setItemType($array['Items Type']);
        }

        return $property;
    }
} 