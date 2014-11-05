<?php
namespace Fmizzell\Systemizer\CodeGenerators;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;

class Object {

    private $metadata;
    private $codeGenerator;
    private $propertyCodeGenerators;

    private $codeGeneratorsNamespace = "\\Fmizzell\\Systemizer\\CodeGenerators\\";

    private $namespace;

    public function __construct(\Fmizzell\Systemizer\Metadata\Object $metadata) {
        $this->setMetadata($metadata);
    }

    private function getPropertyGeneratorClass(\Fmizzell\Systemizer\Metadata\Property $propertyMetadata) {
        $type = $propertyMetadata->getType();

        // If there is a matching code generator for this type of property
        // lets use that.
        if(class_exists($this->codeGeneratorsNamespace . $type)) {
            return $this->codeGeneratorsNamespace . $type;
        }
        else {
            return $this->codeGeneratorsNamespace . "Property";
        }
    }

    private function initializeGenerators() {
        $this->codeGenerator = new ClassGenerator($this->metadata->getName(), $this->namespace);
        foreach($this->metadata->getProperties() as $p) {
            $class = $this->getPropertyGeneratorClass($p);
            $propertyGenerator = new $class($p);
            $propertyGenerator->setNamespace($this->namespace);
            $this->propertyCodeGenerators[] = $propertyGenerator;
        }
    }

    private function setMetadata(\Fmizzell\Systemizer\Metadata\Object $metadata) {
        $this->metadata = $metadata;
    }

    private function setUses() {
        $this->codeGenerator->addUse("Valitron\\Validator");
        foreach($this->propertyCodeGenerators as $pcg) {
            $uses = $pcg->getUses();
            foreach($uses as $use) {
                $this->codeGenerator->addUse($use);
            }
        }
    }

    private function setPropertyDeclarations() {
        foreach ($this->propertyCodeGenerators as $pcg) {
            $this->codeGenerator->addProperty($pcg->getDeclaration());
        }
    }

    private function setConstructor() {
        $method = new MethodGenerator("__construct");
        $this->codeGenerator->addMethod($method);
        foreach ($this->propertyCodeGenerators as $pcg) {
            if ($pcg->getMetadata()->getKind() === \Fmizzell\Systemizer\Metadata\Property::REQUIRED) {
                $method->addParameter($pcg->getParameter());
                $method->addBodyLine($pcg->getSetPropertyLine());
            }
        }
    }

    private function setSetters() {
        foreach ($this->propertyCodeGenerators as $pcg) {
            $this->codeGenerator->addMethod($pcg->getSetter());
        }
    }

    private function setGetters() {
        foreach ($this->propertyCodeGenerators as $pcg) {
            $this->codeGenerator->addMethod($pcg->getGetter());
        }
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    public function generate() {
        $this->initializeGenerators();
        $this->setPropertyDeclarations();
        $this->setConstructor();
        $this->setSetters();
        $this->setGetters();
        $this->setUses();
        return $this->codeGenerator->generate();
    }
} 