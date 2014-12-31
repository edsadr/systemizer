<?php

namespace Fmizzell\Systemizer\CodeGenerators;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Fmizzell\Systemizer\Inflector;

class Collection extends Property {

    public function __construct(\Fmizzell\Systemizer\Metadata\Collection $metadata) {
        $this->metadata = $metadata;
    }

    public function getSetPropertyLine() {
        $propertyName = $this->metadata->getName();
        return new CodeLineGenerator("\$this->{$propertyName} = array();");
    }

    private function singularCapitalizeFirstLetterName() {
        $propertyName = $this->metadata->getName();
        $propertyName = Inflector::singularize($propertyName);
        return ucfirst($propertyName);
    }

    protected function getSetterName() {
        return "set{$this->singularCapitalizeFirstLetterName()}";
    }

    public function getSetter() {
        $setterName = $this->getSetterName();
        $method = new MethodGenerator($setterName);
        $method->setVisibility($this->getVisibility("setter"));

        $fakeProperty = new \Fmizzell\Systemizer\Metadata\Property("value");
        $fakeProperty->setType($this->metadata->getItemType());

        $parameters = $this->metadata->getDimensions();
        $parameters[] = $fakeProperty;

        foreach($parameters as $param) {
            $pcg = new \Fmizzell\Systemizer\CodeGenerators\Property($param);
            $pcg->setNamespace($this->namespace);
            foreach($pcg->getConstraintValidationLines() as $cvl) {
                $method->addBodyLine($cvl);
            }
            $method->addParameter($pcg->getParameter());
        }

        // Validate dimensions, and set value if validation passes.
        // And lets create our array querying string.
        $keys = "";
        $method->addBodyLine(new CodeLineGenerator("\$is_valid = TRUE;"));
        foreach($this->metadata->getDimensions() as $d) {
            $pcg = new \Fmizzell\Systemizer\CodeGenerators\Property($d);
            $method->addBodyLine($pcg->wrapInValidationCode("\$is_valid = FALSE;", TRUE));
            $keys .= "[\${$d->getName()}]";
        }

        $line = "if (\$is_valid) { \$this->{$this->metadata->getName()}{$keys} = \$value; } else { throw new Exception(\"Invalid value\"); }";

        $method->addBodyLine(new CodeLineGenerator($line));

        return $method;
    }

    protected function getGetterName() {
        return "get{$this->singularCapitalizeFirstLetterName()}";
    }

    public function getGetter() {
        $setterName = $this->getGetterName();
        $method = new MethodGenerator($setterName);
        $method->setVisibility($this->getVisibility("getter"));

        $parameters = $this->metadata->getDimensions();

        $keys = "";
        $method->addBodyLine(new CodeLineGenerator("\$is_valid = TRUE;"));

        foreach($parameters as $param) {
            $pcg = new \Fmizzell\Systemizer\CodeGenerators\Property($param);
            $pcg->setNamespace($this->namespace);
            foreach($pcg->getConstraintValidationLines() as $cvl) {
                $method->addBodyLine($cvl);
            }
            $method->addParameter($pcg->getParameter());
            $method->addBodyLine($pcg->wrapInValidationCode("\$is_valid = FALSE;", TRUE));
            $keys .= "[\${$param->getName()}]";
        }

        $var = "\$this->{$this->metadata->getName()}{$keys}";
        $method->addBodyLine(new CodeLineGenerator("if (\$is_valid && !empty({$var})) { return $var; }"));
        $method->addBodyLine(new CodeLineGenerator("else { return NULL; }"));

        return $method;
    }
}