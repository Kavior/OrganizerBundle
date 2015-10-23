<?php

namespace Org\CoreBundle\Propel\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cyclical_events' table.
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
class CyclicalEventsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Org.CoreBundle.Propel.map.CyclicalEventsTableMap';

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
        $this->setName('cyclical_events');
        $this->setPhpName('CyclicalEvents');
        $this->setClassname('Org\\CoreBundle\\Propel\\CyclicalEvents');
        $this->setPackage('src.Org.CoreBundle.Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id_cyclical_event', 'IdCyclicalEvent', 'INTEGER', true, null, null);
        $this->addColumn('cyclical_event_name', 'CyclicalEventName', 'VARCHAR', false, 45, null);
        $this->addColumn('cyclical_event_description', 'CyclicalEventDescription', 'VARCHAR', false, 45, null);
        $this->addColumn('cyclical_event_weight', 'CyclicalEventWeight', 'VARCHAR', false, 45, null);
        $this->addColumn('cyclical_event_month', 'CyclicalEventMonth', 'VARCHAR', false, 45, null);
        $this->addColumn('cyclical_event_week_day', 'CyclicalEventWeekDay', 'VARCHAR', false, 45, null);
        $this->addColumn('cyclical_event_day', 'CyclicalEventDay', 'VARCHAR', false, 45, null);
        $this->addForeignKey('id_user', 'IdUser', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('id_user' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('CyclicalEventHasList', 'Org\\CoreBundle\\Propel\\CyclicalEventHasList', RelationMap::ONE_TO_MANY, array('id_cyclical_event' => 'id_cyclical_event', ), 'CASCADE', 'CASCADE', 'CyclicalEventHasLists');
        $this->addRelation('CustomLists', 'Org\\CoreBundle\\Propel\\CustomLists', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'CustomListss');
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

} // CyclicalEventsTableMap