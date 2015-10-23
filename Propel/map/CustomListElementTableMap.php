<?php

namespace Org\CoreBundle\Propel\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'custom_list_element' table.
 *
 *
 * This class was autogenerated by Propel 1.7.1 on:
 *
 * 10/10/15 12:14:55
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Org.CoreBundle.Propel.map
 */
class CustomListElementTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Org.CoreBundle.Propel.map.CustomListElementTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('custom_list_element');
        $this->setPhpName('CustomListElement');
        $this->setClassname('Org\\CoreBundle\\Propel\\CustomListElement');
        $this->setPackage('src.Org.CoreBundle.Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id_element', 'IdElement', 'INTEGER', true, null, null);
        $this->addForeignKey('custom_list', 'CustomList', 'INTEGER', 'custom_lists', 'id_custom_list', false, null, null);
        $this->addColumn('element_name', 'ElementName', 'VARCHAR', false, 45, null);
        $this->addColumn('element_description', 'ElementDescription', 'VARCHAR', false, 45, null);
        $this->addColumn('element_order', 'ElementOrder', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CustomLists', 'Org\\CoreBundle\\Propel\\CustomLists', RelationMap::MANY_TO_ONE, array('custom_list' => 'id_custom_list', ), 'CASCADE', 'CASCADE');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // CustomListElementTableMap