<?php

namespace Org\CoreBundle\Propel\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Org\CoreBundle\Propel\CustomListElement;
use Org\CoreBundle\Propel\CustomListElementQuery;
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListsPeer;
use Org\CoreBundle\Propel\CustomListsQuery;
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEventHasListQuery;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\CyclicalEventsQuery;
use Org\CoreBundle\Propel\EventHasList;
use Org\CoreBundle\Propel\EventHasListQuery;
use Org\CoreBundle\Propel\Events;
use Org\CoreBundle\Propel\EventsQuery;

abstract class BaseCustomLists extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Org\\CoreBundle\\Propel\\CustomListsPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CustomListsPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id_custom_list field.
     * @var        int
     */
    protected $id_custom_list;

    /**
     * The value for the list_name field.
     * @var        string
     */
    protected $list_name;

    /**
     * The value for the id_user field.
     * @var        int
     */
    protected $id_user;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|CustomListElement[] Collection to store aggregation of CustomListElement objects.
     */
    protected $collCustomListElements;
    protected $collCustomListElementsPartial;

    /**
     * @var        PropelObjectCollection|CyclicalEventHasList[] Collection to store aggregation of CyclicalEventHasList objects.
     */
    protected $collCyclicalEventHasLists;
    protected $collCyclicalEventHasListsPartial;

    /**
     * @var        PropelObjectCollection|EventHasList[] Collection to store aggregation of EventHasList objects.
     */
    protected $collEventHasLists;
    protected $collEventHasListsPartial;

    /**
     * @var        PropelObjectCollection|CyclicalEvents[] Collection to store aggregation of CyclicalEvents objects.
     */
    protected $collCyclicalEventss;

    /**
     * @var        PropelObjectCollection|Events[] Collection to store aggregation of Events objects.
     */
    protected $collEventss;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $cyclicalEventssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $customListElementsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $cyclicalEventHasListsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventHasListsScheduledForDeletion = null;

    /**
     * Get the [id_custom_list] column value.
     *
     * @return int
     */
    public function getIdCustomList()
    {

        return $this->id_custom_list;
    }

    /**
     * Get the [list_name] column value.
     *
     * @return string
     */
    public function getListName()
    {

        return $this->list_name;
    }

    /**
     * Get the [id_user] column value.
     *
     * @return int
     */
    public function getIdUser()
    {

        return $this->id_user;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id_custom_list] column.
     *
     * @param  int $v new value
     * @return CustomLists The current object (for fluent API support)
     */
    public function setIdCustomList($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_custom_list !== $v) {
            $this->id_custom_list = $v;
            $this->modifiedColumns[] = CustomListsPeer::ID_CUSTOM_LIST;
        }


        return $this;
    } // setIdCustomList()

    /**
     * Set the value of [list_name] column.
     *
     * @param  string $v new value
     * @return CustomLists The current object (for fluent API support)
     */
    public function setListName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->list_name !== $v) {
            $this->list_name = $v;
            $this->modifiedColumns[] = CustomListsPeer::LIST_NAME;
        }


        return $this;
    } // setListName()

    /**
     * Set the value of [id_user] column.
     *
     * @param  int $v new value
     * @return CustomLists The current object (for fluent API support)
     */
    public function setIdUser($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_user !== $v) {
            $this->id_user = $v;
            $this->modifiedColumns[] = CustomListsPeer::ID_USER;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setIdUser()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return CustomLists The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CustomListsPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return CustomLists The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = CustomListsPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

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

            $this->id_custom_list = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->list_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->id_user = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->created_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = CustomListsPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating CustomLists object", $e);
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

        if ($this->aUser !== null && $this->id_user !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(CustomListsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CustomListsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collCustomListElements = null;

            $this->collCyclicalEventHasLists = null;

            $this->collEventHasLists = null;

            $this->collCyclicalEventss = null;
            $this->collEventss = null;
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
            $con = Propel::getConnection(CustomListsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CustomListsQuery::create()
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
            $con = Propel::getConnection(CustomListsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(CustomListsPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CustomListsPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CustomListsPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CustomListsPeer::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

            if ($this->cyclicalEventssScheduledForDeletion !== null) {
                if (!$this->cyclicalEventssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->cyclicalEventssScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CyclicalEventHasListQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->cyclicalEventssScheduledForDeletion = null;
                }

                foreach ($this->getCyclicalEventss() as $cyclicalEvents) {
                    if ($cyclicalEvents->isModified()) {
                        $cyclicalEvents->save($con);
                    }
                }
            } elseif ($this->collCyclicalEventss) {
                foreach ($this->collCyclicalEventss as $cyclicalEvents) {
                    if ($cyclicalEvents->isModified()) {
                        $cyclicalEvents->save($con);
                    }
                }
            }

            if ($this->eventssScheduledForDeletion !== null) {
                if (!$this->eventssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->eventssScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    EventHasListQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->eventssScheduledForDeletion = null;
                }

                foreach ($this->getEventss() as $events) {
                    if ($events->isModified()) {
                        $events->save($con);
                    }
                }
            } elseif ($this->collEventss) {
                foreach ($this->collEventss as $events) {
                    if ($events->isModified()) {
                        $events->save($con);
                    }
                }
            }

            if ($this->customListElementsScheduledForDeletion !== null) {
                if (!$this->customListElementsScheduledForDeletion->isEmpty()) {
                    CustomListElementQuery::create()
                        ->filterByPrimaryKeys($this->customListElementsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->customListElementsScheduledForDeletion = null;
                }
            }

            if ($this->collCustomListElements !== null) {
                foreach ($this->collCustomListElements as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cyclicalEventHasListsScheduledForDeletion !== null) {
                if (!$this->cyclicalEventHasListsScheduledForDeletion->isEmpty()) {
                    CyclicalEventHasListQuery::create()
                        ->filterByPrimaryKeys($this->cyclicalEventHasListsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->cyclicalEventHasListsScheduledForDeletion = null;
                }
            }

            if ($this->collCyclicalEventHasLists !== null) {
                foreach ($this->collCyclicalEventHasLists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventHasListsScheduledForDeletion !== null) {
                if (!$this->eventHasListsScheduledForDeletion->isEmpty()) {
                    EventHasListQuery::create()
                        ->filterByPrimaryKeys($this->eventHasListsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventHasListsScheduledForDeletion = null;
                }
            }

            if ($this->collEventHasLists !== null) {
                foreach ($this->collEventHasLists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[] = CustomListsPeer::ID_CUSTOM_LIST;
        if (null !== $this->id_custom_list) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CustomListsPeer::ID_CUSTOM_LIST . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CustomListsPeer::ID_CUSTOM_LIST)) {
            $modifiedColumns[':p' . $index++]  = '`id_custom_list`';
        }
        if ($this->isColumnModified(CustomListsPeer::LIST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`list_name`';
        }
        if ($this->isColumnModified(CustomListsPeer::ID_USER)) {
            $modifiedColumns[':p' . $index++]  = '`id_user`';
        }
        if ($this->isColumnModified(CustomListsPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CustomListsPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `custom_lists` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id_custom_list`':
                        $stmt->bindValue($identifier, $this->id_custom_list, PDO::PARAM_INT);
                        break;
                    case '`list_name`':
                        $stmt->bindValue($identifier, $this->list_name, PDO::PARAM_STR);
                        break;
                    case '`id_user`':
                        $stmt->bindValue($identifier, $this->id_user, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setIdCustomList($pk);

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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = CustomListsPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCustomListElements !== null) {
                    foreach ($this->collCustomListElements as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCyclicalEventHasLists !== null) {
                    foreach ($this->collCyclicalEventHasLists as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventHasLists !== null) {
                    foreach ($this->collEventHasLists as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $criteria = new Criteria(CustomListsPeer::DATABASE_NAME);

        if ($this->isColumnModified(CustomListsPeer::ID_CUSTOM_LIST)) $criteria->add(CustomListsPeer::ID_CUSTOM_LIST, $this->id_custom_list);
        if ($this->isColumnModified(CustomListsPeer::LIST_NAME)) $criteria->add(CustomListsPeer::LIST_NAME, $this->list_name);
        if ($this->isColumnModified(CustomListsPeer::ID_USER)) $criteria->add(CustomListsPeer::ID_USER, $this->id_user);
        if ($this->isColumnModified(CustomListsPeer::CREATED_AT)) $criteria->add(CustomListsPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CustomListsPeer::UPDATED_AT)) $criteria->add(CustomListsPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(CustomListsPeer::DATABASE_NAME);
        $criteria->add(CustomListsPeer::ID_CUSTOM_LIST, $this->id_custom_list);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getIdCustomList();
    }

    /**
     * Generic method to set the primary key (id_custom_list column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIdCustomList($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getIdCustomList();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of CustomLists (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setListName($this->getListName());
        $copyObj->setIdUser($this->getIdUser());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCustomListElements() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCustomListElement($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCyclicalEventHasLists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCyclicalEventHasList($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventHasLists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventHasList($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIdCustomList(NULL); // this is a auto-increment column, so set to default value
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
     * @return CustomLists Clone of current object.
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
     * @return CustomListsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CustomListsPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return CustomLists The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setIdUser(NULL);
        } else {
            $this->setIdUser($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addCustomLists($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->id_user !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->id_user, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addCustomListss($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CustomListElement' == $relationName) {
            $this->initCustomListElements();
        }
        if ('CyclicalEventHasList' == $relationName) {
            $this->initCyclicalEventHasLists();
        }
        if ('EventHasList' == $relationName) {
            $this->initEventHasLists();
        }
    }

    /**
     * Clears out the collCustomListElements collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CustomLists The current object (for fluent API support)
     * @see        addCustomListElements()
     */
    public function clearCustomListElements()
    {
        $this->collCustomListElements = null; // important to set this to null since that means it is uninitialized
        $this->collCustomListElementsPartial = null;

        return $this;
    }

    /**
     * reset is the collCustomListElements collection loaded partially
     *
     * @return void
     */
    public function resetPartialCustomListElements($v = true)
    {
        $this->collCustomListElementsPartial = $v;
    }

    /**
     * Initializes the collCustomListElements collection.
     *
     * By default this just sets the collCustomListElements collection to an empty array (like clearcollCustomListElements());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCustomListElements($overrideExisting = true)
    {
        if (null !== $this->collCustomListElements && !$overrideExisting) {
            return;
        }
        $this->collCustomListElements = new PropelObjectCollection();
        $this->collCustomListElements->setModel('CustomListElement');
    }

    /**
     * Gets an array of CustomListElement objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CustomLists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CustomListElement[] List of CustomListElement objects
     * @throws PropelException
     */
    public function getCustomListElements($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCustomListElementsPartial && !$this->isNew();
        if (null === $this->collCustomListElements || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCustomListElements) {
                // return empty collection
                $this->initCustomListElements();
            } else {
                $collCustomListElements = CustomListElementQuery::create(null, $criteria)
                    ->filterByCustomLists($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCustomListElementsPartial && count($collCustomListElements)) {
                      $this->initCustomListElements(false);

                      foreach ($collCustomListElements as $obj) {
                        if (false == $this->collCustomListElements->contains($obj)) {
                          $this->collCustomListElements->append($obj);
                        }
                      }

                      $this->collCustomListElementsPartial = true;
                    }

                    $collCustomListElements->getInternalIterator()->rewind();

                    return $collCustomListElements;
                }

                if ($partial && $this->collCustomListElements) {
                    foreach ($this->collCustomListElements as $obj) {
                        if ($obj->isNew()) {
                            $collCustomListElements[] = $obj;
                        }
                    }
                }

                $this->collCustomListElements = $collCustomListElements;
                $this->collCustomListElementsPartial = false;
            }
        }

        return $this->collCustomListElements;
    }

    /**
     * Sets a collection of CustomListElement objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $customListElements A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CustomLists The current object (for fluent API support)
     */
    public function setCustomListElements(PropelCollection $customListElements, PropelPDO $con = null)
    {
        $customListElementsToDelete = $this->getCustomListElements(new Criteria(), $con)->diff($customListElements);


        $this->customListElementsScheduledForDeletion = $customListElementsToDelete;

        foreach ($customListElementsToDelete as $customListElementRemoved) {
            $customListElementRemoved->setCustomLists(null);
        }

        $this->collCustomListElements = null;
        foreach ($customListElements as $customListElement) {
            $this->addCustomListElement($customListElement);
        }

        $this->collCustomListElements = $customListElements;
        $this->collCustomListElementsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CustomListElement objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CustomListElement objects.
     * @throws PropelException
     */
    public function countCustomListElements(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCustomListElementsPartial && !$this->isNew();
        if (null === $this->collCustomListElements || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCustomListElements) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCustomListElements());
            }
            $query = CustomListElementQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomLists($this)
                ->count($con);
        }

        return count($this->collCustomListElements);
    }

    /**
     * Method called to associate a CustomListElement object to this object
     * through the CustomListElement foreign key attribute.
     *
     * @param    CustomListElement $l CustomListElement
     * @return CustomLists The current object (for fluent API support)
     */
    public function addCustomListElement(CustomListElement $l)
    {
        if ($this->collCustomListElements === null) {
            $this->initCustomListElements();
            $this->collCustomListElementsPartial = true;
        }

        if (!in_array($l, $this->collCustomListElements->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCustomListElement($l);

            if ($this->customListElementsScheduledForDeletion and $this->customListElementsScheduledForDeletion->contains($l)) {
                $this->customListElementsScheduledForDeletion->remove($this->customListElementsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CustomListElement $customListElement The customListElement object to add.
     */
    protected function doAddCustomListElement($customListElement)
    {
        $this->collCustomListElements[]= $customListElement;
        $customListElement->setCustomLists($this);
    }

    /**
     * @param	CustomListElement $customListElement The customListElement object to remove.
     * @return CustomLists The current object (for fluent API support)
     */
    public function removeCustomListElement($customListElement)
    {
        if ($this->getCustomListElements()->contains($customListElement)) {
            $this->collCustomListElements->remove($this->collCustomListElements->search($customListElement));
            if (null === $this->customListElementsScheduledForDeletion) {
                $this->customListElementsScheduledForDeletion = clone $this->collCustomListElements;
                $this->customListElementsScheduledForDeletion->clear();
            }
            $this->customListElementsScheduledForDeletion[]= $customListElement;
            $customListElement->setCustomLists(null);
        }

        return $this;
    }

    /**
     * Clears out the collCyclicalEventHasLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CustomLists The current object (for fluent API support)
     * @see        addCyclicalEventHasLists()
     */
    public function clearCyclicalEventHasLists()
    {
        $this->collCyclicalEventHasLists = null; // important to set this to null since that means it is uninitialized
        $this->collCyclicalEventHasListsPartial = null;

        return $this;
    }

    /**
     * reset is the collCyclicalEventHasLists collection loaded partially
     *
     * @return void
     */
    public function resetPartialCyclicalEventHasLists($v = true)
    {
        $this->collCyclicalEventHasListsPartial = $v;
    }

    /**
     * Initializes the collCyclicalEventHasLists collection.
     *
     * By default this just sets the collCyclicalEventHasLists collection to an empty array (like clearcollCyclicalEventHasLists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCyclicalEventHasLists($overrideExisting = true)
    {
        if (null !== $this->collCyclicalEventHasLists && !$overrideExisting) {
            return;
        }
        $this->collCyclicalEventHasLists = new PropelObjectCollection();
        $this->collCyclicalEventHasLists->setModel('CyclicalEventHasList');
    }

    /**
     * Gets an array of CyclicalEventHasList objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CustomLists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CyclicalEventHasList[] List of CyclicalEventHasList objects
     * @throws PropelException
     */
    public function getCyclicalEventHasLists($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCyclicalEventHasListsPartial && !$this->isNew();
        if (null === $this->collCyclicalEventHasLists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCyclicalEventHasLists) {
                // return empty collection
                $this->initCyclicalEventHasLists();
            } else {
                $collCyclicalEventHasLists = CyclicalEventHasListQuery::create(null, $criteria)
                    ->filterByCustomLists($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCyclicalEventHasListsPartial && count($collCyclicalEventHasLists)) {
                      $this->initCyclicalEventHasLists(false);

                      foreach ($collCyclicalEventHasLists as $obj) {
                        if (false == $this->collCyclicalEventHasLists->contains($obj)) {
                          $this->collCyclicalEventHasLists->append($obj);
                        }
                      }

                      $this->collCyclicalEventHasListsPartial = true;
                    }

                    $collCyclicalEventHasLists->getInternalIterator()->rewind();

                    return $collCyclicalEventHasLists;
                }

                if ($partial && $this->collCyclicalEventHasLists) {
                    foreach ($this->collCyclicalEventHasLists as $obj) {
                        if ($obj->isNew()) {
                            $collCyclicalEventHasLists[] = $obj;
                        }
                    }
                }

                $this->collCyclicalEventHasLists = $collCyclicalEventHasLists;
                $this->collCyclicalEventHasListsPartial = false;
            }
        }

        return $this->collCyclicalEventHasLists;
    }

    /**
     * Sets a collection of CyclicalEventHasList objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $cyclicalEventHasLists A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CustomLists The current object (for fluent API support)
     */
    public function setCyclicalEventHasLists(PropelCollection $cyclicalEventHasLists, PropelPDO $con = null)
    {
        $cyclicalEventHasListsToDelete = $this->getCyclicalEventHasLists(new Criteria(), $con)->diff($cyclicalEventHasLists);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->cyclicalEventHasListsScheduledForDeletion = clone $cyclicalEventHasListsToDelete;

        foreach ($cyclicalEventHasListsToDelete as $cyclicalEventHasListRemoved) {
            $cyclicalEventHasListRemoved->setCustomLists(null);
        }

        $this->collCyclicalEventHasLists = null;
        foreach ($cyclicalEventHasLists as $cyclicalEventHasList) {
            $this->addCyclicalEventHasList($cyclicalEventHasList);
        }

        $this->collCyclicalEventHasLists = $cyclicalEventHasLists;
        $this->collCyclicalEventHasListsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CyclicalEventHasList objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CyclicalEventHasList objects.
     * @throws PropelException
     */
    public function countCyclicalEventHasLists(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCyclicalEventHasListsPartial && !$this->isNew();
        if (null === $this->collCyclicalEventHasLists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCyclicalEventHasLists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCyclicalEventHasLists());
            }
            $query = CyclicalEventHasListQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomLists($this)
                ->count($con);
        }

        return count($this->collCyclicalEventHasLists);
    }

    /**
     * Method called to associate a CyclicalEventHasList object to this object
     * through the CyclicalEventHasList foreign key attribute.
     *
     * @param    CyclicalEventHasList $l CyclicalEventHasList
     * @return CustomLists The current object (for fluent API support)
     */
    public function addCyclicalEventHasList(CyclicalEventHasList $l)
    {
        if ($this->collCyclicalEventHasLists === null) {
            $this->initCyclicalEventHasLists();
            $this->collCyclicalEventHasListsPartial = true;
        }

        if (!in_array($l, $this->collCyclicalEventHasLists->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCyclicalEventHasList($l);

            if ($this->cyclicalEventHasListsScheduledForDeletion and $this->cyclicalEventHasListsScheduledForDeletion->contains($l)) {
                $this->cyclicalEventHasListsScheduledForDeletion->remove($this->cyclicalEventHasListsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CyclicalEventHasList $cyclicalEventHasList The cyclicalEventHasList object to add.
     */
    protected function doAddCyclicalEventHasList($cyclicalEventHasList)
    {
        $this->collCyclicalEventHasLists[]= $cyclicalEventHasList;
        $cyclicalEventHasList->setCustomLists($this);
    }

    /**
     * @param	CyclicalEventHasList $cyclicalEventHasList The cyclicalEventHasList object to remove.
     * @return CustomLists The current object (for fluent API support)
     */
    public function removeCyclicalEventHasList($cyclicalEventHasList)
    {
        if ($this->getCyclicalEventHasLists()->contains($cyclicalEventHasList)) {
            $this->collCyclicalEventHasLists->remove($this->collCyclicalEventHasLists->search($cyclicalEventHasList));
            if (null === $this->cyclicalEventHasListsScheduledForDeletion) {
                $this->cyclicalEventHasListsScheduledForDeletion = clone $this->collCyclicalEventHasLists;
                $this->cyclicalEventHasListsScheduledForDeletion->clear();
            }
            $this->cyclicalEventHasListsScheduledForDeletion[]= clone $cyclicalEventHasList;
            $cyclicalEventHasList->setCustomLists(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CustomLists is new, it will return
     * an empty collection; or if this CustomLists has previously
     * been saved, it will retrieve related CyclicalEventHasLists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CustomLists.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CyclicalEventHasList[] List of CyclicalEventHasList objects
     */
    public function getCyclicalEventHasListsJoinCyclicalEvents($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CyclicalEventHasListQuery::create(null, $criteria);
        $query->joinWith('CyclicalEvents', $join_behavior);

        return $this->getCyclicalEventHasLists($query, $con);
    }

    /**
     * Clears out the collEventHasLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CustomLists The current object (for fluent API support)
     * @see        addEventHasLists()
     */
    public function clearEventHasLists()
    {
        $this->collEventHasLists = null; // important to set this to null since that means it is uninitialized
        $this->collEventHasListsPartial = null;

        return $this;
    }

    /**
     * reset is the collEventHasLists collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventHasLists($v = true)
    {
        $this->collEventHasListsPartial = $v;
    }

    /**
     * Initializes the collEventHasLists collection.
     *
     * By default this just sets the collEventHasLists collection to an empty array (like clearcollEventHasLists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventHasLists($overrideExisting = true)
    {
        if (null !== $this->collEventHasLists && !$overrideExisting) {
            return;
        }
        $this->collEventHasLists = new PropelObjectCollection();
        $this->collEventHasLists->setModel('EventHasList');
    }

    /**
     * Gets an array of EventHasList objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CustomLists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventHasList[] List of EventHasList objects
     * @throws PropelException
     */
    public function getEventHasLists($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventHasListsPartial && !$this->isNew();
        if (null === $this->collEventHasLists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventHasLists) {
                // return empty collection
                $this->initEventHasLists();
            } else {
                $collEventHasLists = EventHasListQuery::create(null, $criteria)
                    ->filterByCustomLists($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventHasListsPartial && count($collEventHasLists)) {
                      $this->initEventHasLists(false);

                      foreach ($collEventHasLists as $obj) {
                        if (false == $this->collEventHasLists->contains($obj)) {
                          $this->collEventHasLists->append($obj);
                        }
                      }

                      $this->collEventHasListsPartial = true;
                    }

                    $collEventHasLists->getInternalIterator()->rewind();

                    return $collEventHasLists;
                }

                if ($partial && $this->collEventHasLists) {
                    foreach ($this->collEventHasLists as $obj) {
                        if ($obj->isNew()) {
                            $collEventHasLists[] = $obj;
                        }
                    }
                }

                $this->collEventHasLists = $collEventHasLists;
                $this->collEventHasListsPartial = false;
            }
        }

        return $this->collEventHasLists;
    }

    /**
     * Sets a collection of EventHasList objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventHasLists A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CustomLists The current object (for fluent API support)
     */
    public function setEventHasLists(PropelCollection $eventHasLists, PropelPDO $con = null)
    {
        $eventHasListsToDelete = $this->getEventHasLists(new Criteria(), $con)->diff($eventHasLists);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventHasListsScheduledForDeletion = clone $eventHasListsToDelete;

        foreach ($eventHasListsToDelete as $eventHasListRemoved) {
            $eventHasListRemoved->setCustomLists(null);
        }

        $this->collEventHasLists = null;
        foreach ($eventHasLists as $eventHasList) {
            $this->addEventHasList($eventHasList);
        }

        $this->collEventHasLists = $eventHasLists;
        $this->collEventHasListsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventHasList objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventHasList objects.
     * @throws PropelException
     */
    public function countEventHasLists(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventHasListsPartial && !$this->isNew();
        if (null === $this->collEventHasLists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventHasLists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventHasLists());
            }
            $query = EventHasListQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomLists($this)
                ->count($con);
        }

        return count($this->collEventHasLists);
    }

    /**
     * Method called to associate a EventHasList object to this object
     * through the EventHasList foreign key attribute.
     *
     * @param    EventHasList $l EventHasList
     * @return CustomLists The current object (for fluent API support)
     */
    public function addEventHasList(EventHasList $l)
    {
        if ($this->collEventHasLists === null) {
            $this->initEventHasLists();
            $this->collEventHasListsPartial = true;
        }

        if (!in_array($l, $this->collEventHasLists->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEventHasList($l);

            if ($this->eventHasListsScheduledForDeletion and $this->eventHasListsScheduledForDeletion->contains($l)) {
                $this->eventHasListsScheduledForDeletion->remove($this->eventHasListsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EventHasList $eventHasList The eventHasList object to add.
     */
    protected function doAddEventHasList($eventHasList)
    {
        $this->collEventHasLists[]= $eventHasList;
        $eventHasList->setCustomLists($this);
    }

    /**
     * @param	EventHasList $eventHasList The eventHasList object to remove.
     * @return CustomLists The current object (for fluent API support)
     */
    public function removeEventHasList($eventHasList)
    {
        if ($this->getEventHasLists()->contains($eventHasList)) {
            $this->collEventHasLists->remove($this->collEventHasLists->search($eventHasList));
            if (null === $this->eventHasListsScheduledForDeletion) {
                $this->eventHasListsScheduledForDeletion = clone $this->collEventHasLists;
                $this->eventHasListsScheduledForDeletion->clear();
            }
            $this->eventHasListsScheduledForDeletion[]= clone $eventHasList;
            $eventHasList->setCustomLists(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CustomLists is new, it will return
     * an empty collection; or if this CustomLists has previously
     * been saved, it will retrieve related EventHasLists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CustomLists.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventHasList[] List of EventHasList objects
     */
    public function getEventHasListsJoinEvents($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventHasListQuery::create(null, $criteria);
        $query->joinWith('Events', $join_behavior);

        return $this->getEventHasLists($query, $con);
    }

    /**
     * Clears out the collCyclicalEventss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CustomLists The current object (for fluent API support)
     * @see        addCyclicalEventss()
     */
    public function clearCyclicalEventss()
    {
        $this->collCyclicalEventss = null; // important to set this to null since that means it is uninitialized
        $this->collCyclicalEventssPartial = null;

        return $this;
    }

    /**
     * Initializes the collCyclicalEventss collection.
     *
     * By default this just sets the collCyclicalEventss collection to an empty collection (like clearCyclicalEventss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCyclicalEventss()
    {
        $this->collCyclicalEventss = new PropelObjectCollection();
        $this->collCyclicalEventss->setModel('CyclicalEvents');
    }

    /**
     * Gets a collection of CyclicalEvents objects related by a many-to-many relationship
     * to the current object by way of the cyclical_event_has_list cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CustomLists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|CyclicalEvents[] List of CyclicalEvents objects
     */
    public function getCyclicalEventss($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collCyclicalEventss || null !== $criteria) {
            if ($this->isNew() && null === $this->collCyclicalEventss) {
                // return empty collection
                $this->initCyclicalEventss();
            } else {
                $collCyclicalEventss = CyclicalEventsQuery::create(null, $criteria)
                    ->filterByCustomLists($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCyclicalEventss;
                }
                $this->collCyclicalEventss = $collCyclicalEventss;
            }
        }

        return $this->collCyclicalEventss;
    }

    /**
     * Sets a collection of CyclicalEvents objects related by a many-to-many relationship
     * to the current object by way of the cyclical_event_has_list cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $cyclicalEventss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CustomLists The current object (for fluent API support)
     */
    public function setCyclicalEventss(PropelCollection $cyclicalEventss, PropelPDO $con = null)
    {
        $this->clearCyclicalEventss();
        $currentCyclicalEventss = $this->getCyclicalEventss(null, $con);

        $this->cyclicalEventssScheduledForDeletion = $currentCyclicalEventss->diff($cyclicalEventss);

        foreach ($cyclicalEventss as $cyclicalEvents) {
            if (!$currentCyclicalEventss->contains($cyclicalEvents)) {
                $this->doAddCyclicalEvents($cyclicalEvents);
            }
        }

        $this->collCyclicalEventss = $cyclicalEventss;

        return $this;
    }

    /**
     * Gets the number of CyclicalEvents objects related by a many-to-many relationship
     * to the current object by way of the cyclical_event_has_list cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related CyclicalEvents objects
     */
    public function countCyclicalEventss($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collCyclicalEventss || null !== $criteria) {
            if ($this->isNew() && null === $this->collCyclicalEventss) {
                return 0;
            } else {
                $query = CyclicalEventsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCustomLists($this)
                    ->count($con);
            }
        } else {
            return count($this->collCyclicalEventss);
        }
    }

    /**
     * Associate a CyclicalEvents object to this object
     * through the cyclical_event_has_list cross reference table.
     *
     * @param  CyclicalEvents $cyclicalEvents The CyclicalEventHasList object to relate
     * @return CustomLists The current object (for fluent API support)
     */
    public function addCyclicalEvents(CyclicalEvents $cyclicalEvents)
    {
        if ($this->collCyclicalEventss === null) {
            $this->initCyclicalEventss();
        }

        if (!$this->collCyclicalEventss->contains($cyclicalEvents)) { // only add it if the **same** object is not already associated
            $this->doAddCyclicalEvents($cyclicalEvents);
            $this->collCyclicalEventss[] = $cyclicalEvents;

            if ($this->cyclicalEventssScheduledForDeletion and $this->cyclicalEventssScheduledForDeletion->contains($cyclicalEvents)) {
                $this->cyclicalEventssScheduledForDeletion->remove($this->cyclicalEventssScheduledForDeletion->search($cyclicalEvents));
            }
        }

        return $this;
    }

    /**
     * @param	CyclicalEvents $cyclicalEvents The cyclicalEvents object to add.
     */
    protected function doAddCyclicalEvents(CyclicalEvents $cyclicalEvents)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$cyclicalEvents->getCustomListss()->contains($this)) { $cyclicalEventHasList = new CyclicalEventHasList();
            $cyclicalEventHasList->setCyclicalEvents($cyclicalEvents);
            $this->addCyclicalEventHasList($cyclicalEventHasList);

            $foreignCollection = $cyclicalEvents->getCustomListss();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a CyclicalEvents object to this object
     * through the cyclical_event_has_list cross reference table.
     *
     * @param CyclicalEvents $cyclicalEvents The CyclicalEventHasList object to relate
     * @return CustomLists The current object (for fluent API support)
     */
    public function removeCyclicalEvents(CyclicalEvents $cyclicalEvents)
    {
        if ($this->getCyclicalEventss()->contains($cyclicalEvents)) {
            $this->collCyclicalEventss->remove($this->collCyclicalEventss->search($cyclicalEvents));
            if (null === $this->cyclicalEventssScheduledForDeletion) {
                $this->cyclicalEventssScheduledForDeletion = clone $this->collCyclicalEventss;
                $this->cyclicalEventssScheduledForDeletion->clear();
            }
            $this->cyclicalEventssScheduledForDeletion[]= $cyclicalEvents;
        }

        return $this;
    }

    /**
     * Clears out the collEventss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CustomLists The current object (for fluent API support)
     * @see        addEventss()
     */
    public function clearEventss()
    {
        $this->collEventss = null; // important to set this to null since that means it is uninitialized
        $this->collEventssPartial = null;

        return $this;
    }

    /**
     * Initializes the collEventss collection.
     *
     * By default this just sets the collEventss collection to an empty collection (like clearEventss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEventss()
    {
        $this->collEventss = new PropelObjectCollection();
        $this->collEventss->setModel('Events');
    }

    /**
     * Gets a collection of Events objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CustomLists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Events[] List of Events objects
     */
    public function getEventss($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collEventss || null !== $criteria) {
            if ($this->isNew() && null === $this->collEventss) {
                // return empty collection
                $this->initEventss();
            } else {
                $collEventss = EventsQuery::create(null, $criteria)
                    ->filterByCustomLists($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collEventss;
                }
                $this->collEventss = $collEventss;
            }
        }

        return $this->collEventss;
    }

    /**
     * Sets a collection of Events objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CustomLists The current object (for fluent API support)
     */
    public function setEventss(PropelCollection $eventss, PropelPDO $con = null)
    {
        $this->clearEventss();
        $currentEventss = $this->getEventss(null, $con);

        $this->eventssScheduledForDeletion = $currentEventss->diff($eventss);

        foreach ($eventss as $events) {
            if (!$currentEventss->contains($events)) {
                $this->doAddEvents($events);
            }
        }

        $this->collEventss = $eventss;

        return $this;
    }

    /**
     * Gets the number of Events objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Events objects
     */
    public function countEventss($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collEventss || null !== $criteria) {
            if ($this->isNew() && null === $this->collEventss) {
                return 0;
            } else {
                $query = EventsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCustomLists($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventss);
        }
    }

    /**
     * Associate a Events object to this object
     * through the event_has_list cross reference table.
     *
     * @param  Events $events The EventHasList object to relate
     * @return CustomLists The current object (for fluent API support)
     */
    public function addEvents(Events $events)
    {
        if ($this->collEventss === null) {
            $this->initEventss();
        }

        if (!$this->collEventss->contains($events)) { // only add it if the **same** object is not already associated
            $this->doAddEvents($events);
            $this->collEventss[] = $events;

            if ($this->eventssScheduledForDeletion and $this->eventssScheduledForDeletion->contains($events)) {
                $this->eventssScheduledForDeletion->remove($this->eventssScheduledForDeletion->search($events));
            }
        }

        return $this;
    }

    /**
     * @param	Events $events The events object to add.
     */
    protected function doAddEvents(Events $events)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$events->getCustomListss()->contains($this)) { $eventHasList = new EventHasList();
            $eventHasList->setEvents($events);
            $this->addEventHasList($eventHasList);

            $foreignCollection = $events->getCustomListss();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Events object to this object
     * through the event_has_list cross reference table.
     *
     * @param Events $events The EventHasList object to relate
     * @return CustomLists The current object (for fluent API support)
     */
    public function removeEvents(Events $events)
    {
        if ($this->getEventss()->contains($events)) {
            $this->collEventss->remove($this->collEventss->search($events));
            if (null === $this->eventssScheduledForDeletion) {
                $this->eventssScheduledForDeletion = clone $this->collEventss;
                $this->eventssScheduledForDeletion->clear();
            }
            $this->eventssScheduledForDeletion[]= $events;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id_custom_list = null;
        $this->list_name = null;
        $this->id_user = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collCustomListElements) {
                foreach ($this->collCustomListElements as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCyclicalEventHasLists) {
                foreach ($this->collCyclicalEventHasLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventHasLists) {
                foreach ($this->collEventHasLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCyclicalEventss) {
                foreach ($this->collCyclicalEventss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventss) {
                foreach ($this->collEventss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCustomListElements instanceof PropelCollection) {
            $this->collCustomListElements->clearIterator();
        }
        $this->collCustomListElements = null;
        if ($this->collCyclicalEventHasLists instanceof PropelCollection) {
            $this->collCyclicalEventHasLists->clearIterator();
        }
        $this->collCyclicalEventHasLists = null;
        if ($this->collEventHasLists instanceof PropelCollection) {
            $this->collEventHasLists->clearIterator();
        }
        $this->collEventHasLists = null;
        if ($this->collCyclicalEventss instanceof PropelCollection) {
            $this->collCyclicalEventss->clearIterator();
        }
        $this->collCyclicalEventss = null;
        if ($this->collEventss instanceof PropelCollection) {
            $this->collEventss->clearIterator();
        }
        $this->collEventss = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CustomListsPeer::DEFAULT_STRING_FORMAT);
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

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     CustomLists The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = CustomListsPeer::UPDATED_AT;

        return $this;
    }

}
