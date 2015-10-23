<?php

namespace Org\CoreBundle\Propel\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Org\CoreBundle\Propel\CustomListsPeer;
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEventHasListPeer;
use Org\CoreBundle\Propel\CyclicalEventsPeer;
use Org\CoreBundle\Propel\map\CyclicalEventHasListTableMap;

abstract class BaseCyclicalEventHasListPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'cyclical_event_has_list';

    /** the related Propel class for this table */
    const OM_CLASS = 'Org\\CoreBundle\\Propel\\CyclicalEventHasList';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Org\\CoreBundle\\Propel\\map\\CyclicalEventHasListTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 2;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 2;

    /** the column name for the id_cyclical_event field */
    const ID_CYCLICAL_EVENT = 'cyclical_event_has_list.id_cyclical_event';

    /** the column name for the id_list field */
    const ID_LIST = 'cyclical_event_has_list.id_list';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of CyclicalEventHasList objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array CyclicalEventHasList[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. CyclicalEventHasListPeer::$fieldNames[CyclicalEventHasListPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('IdCyclicalEvent', 'IdList', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('idCyclicalEvent', 'idList', ),
        BasePeer::TYPE_COLNAME => array (CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventHasListPeer::ID_LIST, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID_CYCLICAL_EVENT', 'ID_LIST', ),
        BasePeer::TYPE_FIELDNAME => array ('id_cyclical_event', 'id_list', ),
        BasePeer::TYPE_NUM => array (0, 1, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. CyclicalEventHasListPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('IdCyclicalEvent' => 0, 'IdList' => 1, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('idCyclicalEvent' => 0, 'idList' => 1, ),
        BasePeer::TYPE_COLNAME => array (CyclicalEventHasListPeer::ID_CYCLICAL_EVENT => 0, CyclicalEventHasListPeer::ID_LIST => 1, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID_CYCLICAL_EVENT' => 0, 'ID_LIST' => 1, ),
        BasePeer::TYPE_FIELDNAME => array ('id_cyclical_event' => 0, 'id_list' => 1, ),
        BasePeer::TYPE_NUM => array (0, 1, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = CyclicalEventHasListPeer::getFieldNames($toType);
        $key = isset(CyclicalEventHasListPeer::$fieldKeys[$fromType][$name]) ? CyclicalEventHasListPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(CyclicalEventHasListPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, CyclicalEventHasListPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return CyclicalEventHasListPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. CyclicalEventHasListPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(CyclicalEventHasListPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT);
            $criteria->addSelectColumn(CyclicalEventHasListPeer::ID_LIST);
        } else {
            $criteria->addSelectColumn($alias . '.id_cyclical_event');
            $criteria->addSelectColumn($alias . '.id_list');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return CyclicalEventHasList
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = CyclicalEventHasListPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return CyclicalEventHasListPeer::populateObjects(CyclicalEventHasListPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param CyclicalEventHasList $obj A CyclicalEventHasList object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = serialize(array((string) $obj->getIdCyclicalEvent(), (string) $obj->getIdList()));
            } // if key === null
            CyclicalEventHasListPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A CyclicalEventHasList object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof CyclicalEventHasList) {
                $key = serialize(array((string) $value->getIdCyclicalEvent(), (string) $value->getIdList()));
            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or CyclicalEventHasList object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(CyclicalEventHasListPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return CyclicalEventHasList Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(CyclicalEventHasListPeer::$instances[$key])) {
                return CyclicalEventHasListPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (CyclicalEventHasListPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        CyclicalEventHasListPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to cyclical_event_has_list
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null && $row[$startcol + 1] === null) {
            return null;
        }

        return serialize(array((string) $row[$startcol], (string) $row[$startcol + 1]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return array((int) $row[$startcol], (int) $row[$startcol + 1]);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = CyclicalEventHasListPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = CyclicalEventHasListPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CyclicalEventHasListPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (CyclicalEventHasList object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = CyclicalEventHasListPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CyclicalEventHasListPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            CyclicalEventHasListPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related CyclicalEvents table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCyclicalEvents(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CustomLists table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCustomLists(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of CyclicalEventHasList objects pre-filled with their CyclicalEvents objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CyclicalEventHasList objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCyclicalEvents(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);
        }

        CyclicalEventHasListPeer::addSelectColumns($criteria);
        $startcol = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;
        CyclicalEventsPeer::addSelectColumns($criteria);

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CyclicalEventHasListPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CyclicalEventHasListPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CyclicalEventHasListPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CyclicalEventsPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CyclicalEventsPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CyclicalEventsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CyclicalEventsPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (CyclicalEventHasList) to $obj2 (CyclicalEvents)
                $obj2->addCyclicalEventHasList($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CyclicalEventHasList objects pre-filled with their CustomLists objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CyclicalEventHasList objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCustomLists(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);
        }

        CyclicalEventHasListPeer::addSelectColumns($criteria);
        $startcol = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;
        CustomListsPeer::addSelectColumns($criteria);

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CyclicalEventHasListPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CyclicalEventHasListPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CyclicalEventHasListPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CustomListsPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CustomListsPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CustomListsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CustomListsPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (CyclicalEventHasList) to $obj2 (CustomLists)
                $obj2->addCyclicalEventHasList($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of CyclicalEventHasList objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CyclicalEventHasList objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);
        }

        CyclicalEventHasListPeer::addSelectColumns($criteria);
        $startcol2 = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;

        CyclicalEventsPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CyclicalEventsPeer::NUM_HYDRATE_COLUMNS;

        CustomListsPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CustomListsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CyclicalEventHasListPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CyclicalEventHasListPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CyclicalEventHasListPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined CyclicalEvents rows

            $key2 = CyclicalEventsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CyclicalEventsPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CyclicalEventsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CyclicalEventsPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (CyclicalEventHasList) to the collection in $obj2 (CyclicalEvents)
                $obj2->addCyclicalEventHasList($obj1);
            } // if joined row not null

            // Add objects for joined CustomLists rows

            $key3 = CustomListsPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = CustomListsPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = CustomListsPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CustomListsPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (CyclicalEventHasList) to the collection in $obj3 (CustomLists)
                $obj3->addCyclicalEventHasList($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CyclicalEvents table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCyclicalEvents(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CustomLists table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCustomLists(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CyclicalEventHasListPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of CyclicalEventHasList objects pre-filled with all related objects except CyclicalEvents.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CyclicalEventHasList objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCyclicalEvents(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);
        }

        CyclicalEventHasListPeer::addSelectColumns($criteria);
        $startcol2 = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;

        CustomListsPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CustomListsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CyclicalEventHasListPeer::ID_LIST, CustomListsPeer::ID_CUSTOM_LIST, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CyclicalEventHasListPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CyclicalEventHasListPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CyclicalEventHasListPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CustomLists rows

                $key2 = CustomListsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CustomListsPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CustomListsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CustomListsPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (CyclicalEventHasList) to the collection in $obj2 (CustomLists)
                $obj2->addCyclicalEventHasList($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CyclicalEventHasList objects pre-filled with all related objects except CustomLists.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CyclicalEventHasList objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCustomLists(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);
        }

        CyclicalEventHasListPeer::addSelectColumns($criteria);
        $startcol2 = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS;

        CyclicalEventsPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CyclicalEventsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, CyclicalEventsPeer::ID_CYCLICAL_EVENT, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CyclicalEventHasListPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CyclicalEventHasListPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CyclicalEventHasListPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CyclicalEventHasListPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CyclicalEvents rows

                $key2 = CyclicalEventsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CyclicalEventsPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CyclicalEventsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CyclicalEventsPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (CyclicalEventHasList) to the collection in $obj2 (CyclicalEvents)
                $obj2->addCyclicalEventHasList($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(CyclicalEventHasListPeer::DATABASE_NAME)->getTable(CyclicalEventHasListPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseCyclicalEventHasListPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseCyclicalEventHasListPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Org\CoreBundle\Propel\map\CyclicalEventHasListTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return CyclicalEventHasListPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a CyclicalEventHasList or Criteria object.
     *
     * @param      mixed $values Criteria or CyclicalEventHasList object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from CyclicalEventHasList object
        }


        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a CyclicalEventHasList or Criteria object.
     *
     * @param      mixed $values Criteria or CyclicalEventHasList object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT);
            $value = $criteria->remove(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT);
            if ($value) {
                $selectCriteria->add(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);
            }

            $comparison = $criteria->getComparison(CyclicalEventHasListPeer::ID_LIST);
            $value = $criteria->remove(CyclicalEventHasListPeer::ID_LIST);
            if ($value) {
                $selectCriteria->add(CyclicalEventHasListPeer::ID_LIST, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(CyclicalEventHasListPeer::TABLE_NAME);
            }

        } else { // $values is CyclicalEventHasList object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the cyclical_event_has_list table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(CyclicalEventHasListPeer::TABLE_NAME, $con, CyclicalEventHasListPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CyclicalEventHasListPeer::clearInstancePool();
            CyclicalEventHasListPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a CyclicalEventHasList or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or CyclicalEventHasList object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            CyclicalEventHasListPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof CyclicalEventHasList) { // it's a model object
            // invalidate the cache for this single object
            CyclicalEventHasListPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CyclicalEventHasListPeer::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(CyclicalEventHasListPeer::ID_LIST, $value[1]));
                $criteria->addOr($criterion);
                // we can invalidate the cache for this single PK
                CyclicalEventHasListPeer::removeInstanceFromPool($value);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(CyclicalEventHasListPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            CyclicalEventHasListPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given CyclicalEventHasList object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param CyclicalEventHasList $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(CyclicalEventHasListPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(CyclicalEventHasListPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(CyclicalEventHasListPeer::DATABASE_NAME, CyclicalEventHasListPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param   int $id_cyclical_event
     * @param   int $id_list
     * @param      PropelPDO $con
     * @return CyclicalEventHasList
     */
    public static function retrieveByPK($id_cyclical_event, $id_list, PropelPDO $con = null) {
        $_instancePoolKey = serialize(array((string) $id_cyclical_event, (string) $id_list));
         if (null !== ($obj = CyclicalEventHasListPeer::getInstanceFromPool($_instancePoolKey))) {
             return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $criteria = new Criteria(CyclicalEventHasListPeer::DATABASE_NAME);
        $criteria->add(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $id_cyclical_event);
        $criteria->add(CyclicalEventHasListPeer::ID_LIST, $id_list);
        $v = CyclicalEventHasListPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
} // BaseCyclicalEventHasListPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseCyclicalEventHasListPeer::buildTableMap();

