<?php
namespace DoctrineExtensions;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class TablePrefix
{
    protected $prefixAll = NULL;
    protected $prefixMtM = NULL;
    protected static $c = 0;

    public function __construct($prefixAll, $prefixMtM) {
        $this->prefixAll = $prefixAll;
        $this->prefixMtM = $prefixMtM;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
        $classMetadata = $eventArgs->getClassMetadata();
        if(isset($this->prefixAll)) $classMetadata->setTableName($this->prefixAll . $classMetadata->getTableName());
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if (isset($this->prefixMtM) && $mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                if(!isset($classMetadata->associationMappings[$fieldName]['joinTable'])) continue;
                if(!isset($classMetadata->associationMappings[$fieldName]['joinTable']['name'])) continue;
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefixMtM . $mappedTableName;
            }
        }
    }

}