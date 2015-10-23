<?php

namespace Org\CoreBundle\Propel\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListsQuery;
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEventHasListPeer;
use Org\CoreBundle\Propel\CyclicalEventHasListQuery;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\CyclicalEventsQuery;

abstract class BaseCyclicalEventHasList extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Org\\CoreBundle\\Propel\\CyclicalEventHasListPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CyclicalEventHasListPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id_cyclical_event field.
     * @var        int
     */
    protected $id_cyclical_event;

    /**
     * The value for the id_list field.
     * @var        int
     */
    protected $id_list;

    /**
     * @var        CyclicalEvents
     */
    protected $aCyclicalEvents;

    /**
     * @var        CustomLists
     */
    protected $aCustomLists;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * Get the [id_cyclical_event] column value.
     *
     * @return int
     */
    public function getIdCyclicalEvent()
    {

        return $this->id_cyclical_event;
    }

    /**
     * Get the [id_list] column value.
     *
     * @return int
     */
    public function getIdList()
    {

        return $this->id_list;
    }

    /**
     * Set the value of [id_cyclical_event] column.
     *
     * @param  int $v new value
     * @return CyclicalEventHasList The current object (for fluent API support)
     */
    public function setIdCyclicalEvent($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_cyclical_event !== $v) {
            $this->id_cyclical_event = $v;
            $this->modifiedColumns[] = CyclicalEventHasListPeer::ID_CYCLICAL_EVENT;
        }

        if ($this->aCyclicalEvents !== null && $this->aCyclicalEvents->getIdCyclicalEvent() !== $v) {
            $this->aCyclicalEvents = null;
        }


        return $this;
    } // setIdCyclicalEvent()

    /**
     * Set the value of [id_list] column.
     *
     * @param  int $v new value
     * @return CyclicalEventHasList The current object (for fluent API support)
     */
    public function setIdList($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_list !== $v) {
            $this->id_list = $v;
            $this->modifiedColumns[] = CyclicalEventHasListPeer::ID_LIST;
        }

        if ($this->aCustomLists !== null && $this->aCustomLists->getIdCustomList() !== $v) {
            $this->aCustomLists = null;
        }


        return $this;
    } // setIdList()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id_cyclical_event = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->id_list = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 2; // 2 = CyclicalEventHasListPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating CyclicalEventHasList object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aCyclicalEvents !== null && $this->id_cyclical_event !== $this->aCyclicalEvents->getIdCyclicalEvent()) {
            $this->aCyclicalEvents = null;
        }
        if ($this->aCustomLists !== null && $this->id_list !== $this->aCustomLists->getIdCustomList()) {
            $this->aCustomLists = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CyclicalEventHasListPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCyclicalEvents = null;
            $this->aCustomLists = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CyclicalEventHasListQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CyclicalEventHasListPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCyclicalEvents !== null) {
                if ($this->aCyclicalEvents->isModified() || $this->aCyclicalEvents->isNew()) {
                    $affectedRows += $this->aCyclicalEvents->save($con);
                }
                $this->setCyclicalEvents($this->aCyclicalEvents);
            }

            if ($this->aCustomLists !== null) {
                if ($this->aCustomLists->isModified() || $this->aCustomLists->isNew()) {
                    $affectedRows += $this->aCustomLists->save($con);
                }
                $this->setCustomLists($this->aCustomLists);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT)) {
            $modifiedColumns[':p' . $index++]  = '`id_cyclical_event`';
        }
        if ($this->isColumnModified(CyclicalEventHasListPeer::ID_LIST)) {
            $modifiedColumns[':p' . $index++]  = '`id_list`';
        }

        $sql = sprintf(
            'INSERT INTO `cyclical_event_has_list` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id_cyclical_event`':
                        $stmt->bindValue($identifier, $this->id_cyclical_event, PDO::PARAM_INT);
                        break;
                    case '`id_list`':
                        $stmt->bindValue($identifier, $this->id_list, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCyclicalEvents !== null) {
                if (!$this->aCyclicalEvents->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCyclicalEvents->getValidationFailures());
                }
            }

            if ($this->aCustomLists !== null) {
                if (!$this->aCustomLists->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCustomLists->getValidationFailures());
                }
            }


            if (($retval = CyclicalEventHasListPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CyclicalEventHasListPeer::DATABASE_NAME);

        if ($this->isColumnModified(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT)) $criteria->add(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $this->id_cyclical_event);
        if ($this->isColumnModified(CyclicalEventHasListPeer::ID_LIST)) $criteria->add(CyclicalEventHasListPeer::ID_LIST, $this->id_list);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CyclicalEventHasListPeer::DATABASE_NAME);
        $criteria->add(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $this->id_cyclical_event);
        $criteria->add(CyclicalEventHasListPeer::ID_LIST, $this->id_list);

        return $criteria;
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getIdCyclicalEvent();
        $pks[1] = $this->getIdList();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setIdCyclicalEvent($keys[0]);
        $this->setIdList($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getIdCyclicalEvent()) && (null === $this->getIdList());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of CyclicalEventHasList (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setIdCyclicalEvent($this->getIdCyclicalEvent());
        $copyObj->setIdList($this->getIdList());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return CyclicalEventHasList Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CyclicalEventHasListPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CyclicalEventHasListPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CyclicalEvents object.
     *
     * @param                  CyclicalEvents $v
     * @return CyclicalEventHasList The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCyclicalEvents(CyclicalEvents $v = null)
    {
        if ($v === null) {
            $this->setIdCyclicalEvent(NULL);
        } else {
            $this->setIdCyclicalEvent($v->getIdCyclicalEvent());
        }

        $this->aCyclicalEvents = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CyclicalEvents object, it will not be re-added.
        if ($v !== null) {
            $v->addCyclicalEventHasList($this);
        }


        return $this;
    }


    /**
     * Get the associated CyclicalEvents object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CyclicalEvents The associated CyclicalEvents object.
     * @throws PropelException
     */
    public function getCyclicalEvents(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCyclicalEvents === null && ($this->id_cyclical_event !== null) && $doQuery) {
            $this->aCyclicalEvents = CyclicalEventsQuery::create()->findPk($this->id_cyclical_event, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCyclicalEvents->addCyclicalEventHasLists($this);
             */
        }

        return $this->aCyclicalEvents;
    }

    /**
     * Declares an association between this object and a CustomLists object.
     *
     * @param                  CustomLists $v
     * @return CyclicalEventHasList The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCustomLists(CustomLists $v = null)
    {
        if ($v === null) {
            $this->setIdList(NULL);
        } else {
            $this->setIdList($v->getIdCustomList());
        }

        $this->aCustomLists = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CustomLists object, it will not be re-added.
        if ($v !== null) {
            $v->addCyclicalEventHasList($this);
        }


        return $this;
    }


    /**
     * Get the associated CustomLists object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CustomLists The associated CustomLists object.
     * @throws PropelException
     */
    public function getCustomLists(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCustomLists === null && ($this->id_list !== null) && $doQuery) {
            $this->aCustomLists = CustomListsQuery::create()->findPk($this->id_list, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCustomLists->addCyclicalEventHasLists($this);
             */
        }

        return $this->aCustomLists;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id_cyclical_event = null;
        $this->id_list = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->aCyclicalEvents instanceof Persistent) {
              $this->aCyclicalEvents->clearAllReferences($deep);
            }
            if ($this->aCustomLists instanceof Persistent) {
              $this->aCustomLists->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aCyclicalEvents = null;
        $this->aCustomLists = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CyclicalEventHasListPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
