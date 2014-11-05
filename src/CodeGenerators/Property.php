<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 10/17/14
 * Time: 7:00 PM
 */

namespace Fmizzell\Systemizer\CodeGenerators;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;

class Property {
    protected $metadata;

    private $primitiveTypesNamespace = "Fmizzell\\Systemizer\\PrimitiveTypes\\";

    private $primitive;

    private $uses = array();

    protected $namespace = "";

    public function __construct(\Fmizzell\Systemizer\Metadata\Property $metadata) {
        $this->metadata = $metadata;

        $propertyName = $this->metadata->getName();
        if($this->isPrimitive()) {
            $type_class = "{$this->primitiveTypesNamespace}{$this->metadata->getType()}";
            $this->primitive = new $type_class($propertyName);
        }
        else {
            $this->primitive = new \Fmizzell\Systemizer\PrimitiveTypes\NotApplicable($propertyName);
        }

        foreach($this->metadata->getConstraints() as $c) {
            $this->primitive->setConstraint($c);
        }
    }

    public function getDeclaration() {
        $prop = new PropertyGenerator($this->metadata->getName());
        $prop->setVisibility("protected");
        return $prop;
    }

    protected function getGetterName() {
        $name = ucfirst($this->metadata->getName());
        return "get{$name}";
    }

    public function getGetter() {
        $propertyName = $this->metadata->getName();
        $getterName = $this->getGetterName($propertyName);
        $method = new MethodGenerator($getterName);
        $method->setVisibility($this->getVisibility("getter"));
        $codeLine = new CodeLineGenerator("return \$this->{$propertyName};");
        $method->addBodyLine($codeLine);
        return $method;
    }

    protected function getSetterName() {
        $name = ucfirst($this->metadata->getName());
        return "set{$name}";
    }

    public function getSetter() {
        $name = $this->metadata->getName();
        $setterName = $this->getSetterName();
        $method = new MethodGenerator($setterName);
        $method->setVisibility($this->getVisibility("setter"));
        $method->addParameter($this->getParameter());
        foreach($this->getConstraintValidationLines() as $line) {
            $method->addBodyLine($line);
        }
        $method->addBodyLine($this->wrapInValidationCode("\$this->{$name} = \${$name};"));
        return $method;
    }

    public function getParameter() {
        $propertyName = $this->metadata->getName();
        $param = new ParameterGenerator($propertyName);

        if(!$this->isPrimitive()) {
            if(!empty($this->namespace)) {
                $fullObjectPath = $this->namespace . "\\" . $this->metadata->getType();
                $this->uses[] = $fullObjectPath;
                $param->setType("\\" . $fullObjectPath);
            }
            else {
                $param->setType($this->metadata->getType());
            }
        }

        return $param;
    }

    public function getSetPropertyLine() {
        $propertyName = $this->metadata->getName();
        return new CodeLineGenerator("\$this->{$this->getSetterName($propertyName)}(\${$propertyName});");
    }

    protected function wrapInValidationCode($string, $negate = FALSE) {
        $primitive = $this->isPrimitive();

        // If the type is a primitive type, we use the types validation.
        $line = "";
        if ($primitive) {
            $validation_line = $this->primitive->getValidationLine();
            if ($negate) {
                $line .= "if (!($validation_line)) { ";
            }
            else {
                $line .= "if ($validation_line) { ";

            }
        }

        $line .= $string;

        if ($primitive) {
            $line .= " }";
        }
        return new CodeLineGenerator($line);
    }

    protected function getConstraintValidationLines() {
        $lines = array();
        if($this->primitive) {
            foreach ($this->primitive->getConstraintLines() as $cl) {
                $lines[] = new CodeLineGenerator($cl);
            }
        }
        return $lines;
    }

    private function isPrimitive() {
        // If the type is a primitive type, we use the types validation.
        $primitive = FALSE;
        $type = $this->metadata->getType();
        $type_class = "{$this->primitiveTypesNamespace}{$type}";

        if(class_exists($type_class)) {
            $primitive = TRUE;
        }
        return $primitive;
    }

    public function getMetadata() {
        return $this->metadata;
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    public function getUses() {
        return $this->uses;
    }

    protected function getVisibility($accessorType) {
        $kind = $this->metadata->getKind();
        if ($accessorType == "setter") {
            if($kind === \Fmizzell\Systemizer\Metadata\Property::OPTIONAL) {
                return "public";
            }
            elseif ($kind === \Fmizzell\Systemizer\Metadata\Property::REQUIRED ||
                $kind === \Fmizzell\Systemizer\Metadata\Property::INTERNAL) {
                return "protected";
            }
        }
        else if ($accessorType == "getter") {
            if($kind === \Fmizzell\Systemizer\Metadata\Property::INTERNAL) {
                return "protected";
            }
            elseif ($kind === \Fmizzell\Systemizer\Metadata\Property::REQUIRED ||
                $kind === \Fmizzell\Systemizer\Metadata\Property::OPTIONAL) {
                return "public";
            }
        }
        throw new \Exception("Incorrect accessor type or property kind");
    }
} 