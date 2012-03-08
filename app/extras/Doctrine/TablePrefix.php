<?php
namespace DoctrineExtensions;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class TablePrefix
{
    protected $prefix = '';
    protected static $c = 0;

    public function __construct($prefix) {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
        $classMetadata = $eventArgs->getClassMetadata();
        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            //if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                if(!isset($classMetadata->associationMappings[$fieldName]['joinTable'])) continue;
                if(!isset($classMetadata->associationMappings[$fieldName]['joinTable']['name'])) continue;
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . self::$c++ . $mappedTableName;
            //}
        }
    }

}