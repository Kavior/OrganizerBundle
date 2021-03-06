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
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListsQuery;
use Org\CoreBundle\Propel\EventHasList;
use Org\CoreBundle\Propel\EventHasListQuery;
use Org\CoreBundle\Propel\Events;
use Org\CoreBundle\Propel\EventsPeer;
use Org\CoreBundle\Propel\EventsQuery;

abstract class BaseEvents extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Org\\CoreBundle\\Propel\\EventsPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EventsPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id_event field.
     * @var        int
     */
    protected $id_event;

    /**
     * The value for the event_name field.
     * @var        string
     */
    protected $event_name;

    /**
     * The value for the event_description field.
     * @var        string
     */
    protected $event_description;

    /**
     * The value for the event_weight field.
     * @var        int
     */
    protected $event_weight;

    /**
     * The value for the event_date field.
     * @var        string
     */
    protected $event_date;

    /**
     * The value for the event_order field.
     * @var        int
     */
    protected $event_order;

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
     * @var        PropelObjectCollection|EventHasList[] Collection to store aggregation of EventHasList objects.
     */
    protected $collEventHasLists;
    protected $collEventHasListsPartial;

    /**
     * @var        PropelObjectCollection|CustomLists[] Collection to store aggregation of CustomLists objects.
     */
    protected $collCustomListss;

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
    protected $customListssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventHasListsScheduledForDeletion = null;

    /**
     * Get the [id_event] column value.
     *
     * @return int
     */
    public function getIdEvent()
    {

        return $this->id_event;
    }

    /**
     * Get the [event_name] column value.
     *
     * @return string
     */
    public function getEventName()
    {

        return $this->event_name;
    }

    /**
     * Get the [event_description] column value.
     *
     * @return string
     */
    public function getEventDescription()
    {

        return $this->event_description;
    }

    /**
     * Get the [event_weight] column value.
     *
     * @return int
     */
    public function getEventWeight()
    {

        return $this->event_weight;
    }

    /**
     * Get the [event_date] column value.
     *
     * @return string
     */
    public function getEventDate()
    {

        return $this->event_date;
    }

    /**
     * Get the [event_order] column value.
     *
     * @return int
     */
    public function getEventOrder()
    {

        return $this->event_order;
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
     * Set the value of [id_event] column.
     *
     * @param  int $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setIdEvent($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_event !== $v) {
            $this->id_event = $v;
            $this->modifiedColumns[] = EventsPeer::ID_EVENT;
        }


        return $this;
    } // setIdEvent()

    /**
     * Set the value of [event_name] column.
     *
     * @param  string $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setEventName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_name !== $v) {
            $this->event_name = $v;
            $this->modifiedColumns[] = EventsPeer::EVENT_NAME;
        }


        return $this;
    } // setEventName()

    /**
     * Set the value of [event_description] column.
     *
     * @param  string $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setEventDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_description !== $v) {
            $this->event_description = $v;
            $this->modifiedColumns[] = EventsPeer::EVENT_DESCRIPTION;
        }


        return $this;
    } // setEventDescription()

    /**
     * Set the value of [event_weight] column.
     *
     * @param  int $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setEventWeight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->event_weight !== $v) {
            $this->event_weight = $v;
            $this->modifiedColumns[] = EventsPeer::EVENT_WEIGHT;
        }


        return $this;
    } // setEventWeight()

    /**
     * Set the value of [event_date] column.
     *
     * @param  string $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setEventDate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_date !== $v) {
            $this->event_date = $v;
            $this->modifiedColumns[] = EventsPeer::EVENT_DATE;
        }


        return $this;
    } // setEventDate()

    /**
     * Set the value of [event_order] column.
     *
     * @param  int $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setEventOrder($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->event_order !== $v) {
            $this->event_order = $v;
            $this->modifiedColumns[] = EventsPeer::EVENT_ORDER;
        }


        return $this;
    } // setEventOrder()

    /**
     * Set the value of [id_user] column.
     *
     * @param  int $v new value
     * @return Events The current object (for fluent API support)
     */
    public function setIdUser($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id_user !== $v) {
            $this->id_user = $v;
            $this->modifiedColumns[] = EventsPeer::ID_USER;
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
     * @return Events The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = EventsPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Events The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = EventsPeer::UPDATED_AT;
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

            $this->id_event = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->event_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->event_description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->event_weight = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->event_date = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->event_order = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->id_user = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = EventsPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Events object", $e);
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
            $con = Propel::getConnection(EventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EventsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collEventHasLists = null;

            $this->collCustomListss = null;
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
            $con = Propel::getConnection(EventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EventsQuery::create()
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
            $con = Propel::getConnection(EventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(EventsPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(EventsPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EventsPeer::UPDATED_AT)) {
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
                EventsPeer::addInstanceToPool($this);
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

            if ($this->customListssScheduledForDeletion !== null) {
                if (!$this->customListssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->customListssScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    EventHasListQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->customListssScheduledForDeletion = null;
                }

                foreach ($this->getCustomListss() as $customLists) {
                    if ($customLists->isModified()) {
                        $customLists->save($con);
                    }
                }
            } elseif ($this->collCustomListss) {
                foreach ($this->collCustomListss as $customLists) {
                    if ($customLists->isModified()) {
                        $customLists->save($con);
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

        $this->modifiedColumns[] = EventsPeer::ID_EVENT;
        if (null !== $this->id_event) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventsPeer::ID_EVENT . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventsPeer::ID_EVENT)) {
            $modifiedColumns[':p' . $index++]  = '`id_event`';
        }
        if ($this->isColumnModified(EventsPeer::EVENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`event_name`';
        }
        if ($this->isColumnModified(EventsPeer::EVENT_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`event_description`';
        }
        if ($this->isColumnModified(EventsPeer::EVENT_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`event_weight`';
        }
        if ($this->isColumnModified(EventsPeer::EVENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`event_date`';
        }
        if ($this->isColumnModified(EventsPeer::EVENT_ORDER)) {
            $modifiedColumns[':p' . $index++]  = '`event_order`';
        }
        if ($this->isColumnModified(EventsPeer::ID_USER)) {
            $modifiedColumns[':p' . $index++]  = '`id_user`';
        }
        if ($this->isColumnModified(EventsPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(EventsPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `events` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id_event`':
                        $stmt->bindValue($identifier, $this->id_event, PDO::PARAM_INT);
                        break;
                    case '`event_name`':
                        $stmt->bindValue($identifier, $this->event_name, PDO::PARAM_STR);
                        break;
                    case '`event_description`':
                        $stmt->bindValue($identifier, $this->event_description, PDO::PARAM_STR);
                        break;
                    case '`event_weight`':
                        $stmt->bindValue($identifier, $this->event_weight, PDO::PARAM_INT);
                        break;
                    case '`event_date`':
                        $stmt->bindValue($identifier, $this->event_date, PDO::PARAM_STR);
                        break;
                    case '`event_order`':
                        $stmt->bindValue($identifier, $this->event_order, PDO::PARAM_INT);
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
        $this->setIdEvent($pk);

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


            if (($retval = EventsPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $criteria = new Criteria(EventsPeer::DATABASE_NAME);

        if ($this->isColumnModified(EventsPeer::ID_EVENT)) $criteria->add(EventsPeer::ID_EVENT, $this->id_event);
        if ($this->isColumnModified(EventsPeer::EVENT_NAME)) $criteria->add(EventsPeer::EVENT_NAME, $this->event_name);
        if ($this->isColumnModified(EventsPeer::EVENT_DESCRIPTION)) $criteria->add(EventsPeer::EVENT_DESCRIPTION, $this->event_description);
        if ($this->isColumnModified(EventsPeer::EVENT_WEIGHT)) $criteria->add(EventsPeer::EVENT_WEIGHT, $this->event_weight);
        if ($this->isColumnModified(EventsPeer::EVENT_DATE)) $criteria->add(EventsPeer::EVENT_DATE, $this->event_date);
        if ($this->isColumnModified(EventsPeer::EVENT_ORDER)) $criteria->add(EventsPeer::EVENT_ORDER, $this->event_order);
        if ($this->isColumnModified(EventsPeer::ID_USER)) $criteria->add(EventsPeer::ID_USER, $this->id_user);
        if ($this->isColumnModified(EventsPeer::CREATED_AT)) $criteria->add(EventsPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(EventsPeer::UPDATED_AT)) $criteria->add(EventsPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(EventsPeer::DATABASE_NAME);
        $criteria->add(EventsPeer::ID_EVENT, $this->id_event);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getIdEvent();
    }

    /**
     * Generic method to set the primary key (id_event column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIdEvent($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getIdEvent();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Events (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventName($this->getEventName());
        $copyObj->setEventDescription($this->getEventDescription());
        $copyObj->setEventWeight($this->getEventWeight());
        $copyObj->setEventDate($this->getEventDate());
        $copyObj->setEventOrder($this->getEventOrder());
        $copyObj->setIdUser($this->getIdUser());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
            $copyObj->setIdEvent(NULL); // this is a auto-increment column, so set to default value
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
     * @return Events Clone of current object.
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
     * @return EventsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EventsPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Events The current object (for fluent API support)
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
            $v->addEvents($this);
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
                $this->aUser->addEventss($this);
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
        if ('EventHasList' == $relationName) {
            $this->initEventHasLists();
        }
    }

    /**
     * Clears out the collEventHasLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Events The current object (for fluent API support)
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
     * If this Events is new, it will return
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
                    ->filterByEvents($this)
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
     * @return Events The current object (for fluent API support)
     */
    public function setEventHasLists(PropelCollection $eventHasLists, PropelPDO $con = null)
    {
        $eventHasListsToDelete = $this->getEventHasLists(new Criteria(), $con)->diff($eventHasLists);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventHasListsScheduledForDeletion = clone $eventHasListsToDelete;

        foreach ($eventHasListsToDelete as $eventHasListRemoved) {
            $eventHasListRemoved->setEvents(null);
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
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collEventHasLists);
    }

    /**
     * Method called to associate a EventHasList object to this object
     * through the EventHasList foreign key attribute.
     *
     * @param    EventHasList $l EventHasList
     * @return Events The current object (for fluent API support)
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
        $eventHasList->setEvents($this);
    }

    /**
     * @param	EventHasList $eventHasList The eventHasList object to remove.
     * @return Events The current object (for fluent API support)
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
            $eventHasList->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related EventHasLists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventHasList[] List of EventHasList objects
     */
    public function getEventHasListsJoinCustomLists($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventHasListQuery::create(null, $criteria);
        $query->joinWith('CustomLists', $join_behavior);

        return $this->getEventHasLists($query, $con);
    }

    /**
     * Clears out the collCustomListss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Events The current object (for fluent API support)
     * @see        addCustomListss()
     */
    public function clearCustomListss()
    {
        $this->collCustomListss = null; // important to set this to null since that means it is uninitialized
        $this->collCustomListssPartial = null;

        return $this;
    }

    /**
     * Initializes the collCustomListss collection.
     *
     * By default this just sets the collCustomListss collection to an empty collection (like clearCustomListss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCustomListss()
    {
        $this->collCustomListss = new PropelObjectCollection();
        $this->collCustomListss->setModel('CustomLists');
    }

    /**
     * Gets a collection of CustomLists objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Events is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|CustomLists[] List of CustomLists objects
     */
    public function getCustomListss($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collCustomListss || null !== $criteria) {
            if ($this->isNew() && null === $this->collCustomListss) {
                // return empty collection
                $this->initCustomListss();
            } else {
                $collCustomListss = CustomListsQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCustomListss;
                }
                $this->collCustomListss = $collCustomListss;
            }
        }

        return $this->collCustomListss;
    }

    /**
     * Sets a collection of CustomLists objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $customListss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Events The current object (for fluent API support)
     */
    public function setCustomListss(PropelCollection $customListss, PropelPDO $con = null)
    {
        $this->clearCustomListss();
        $currentCustomListss = $this->getCustomListss(null, $con);

        $this->customListssScheduledForDeletion = $currentCustomListss->diff($customListss);

        foreach ($customListss as $customLists) {
            if (!$currentCustomListss->contains($customLists)) {
                $this->doAddCustomLists($customLists);
            }
        }

        $this->collCustomListss = $customListss;

        return $this;
    }

    /**
     * Gets the number of CustomLists objects related by a many-to-many relationship
     * to the current object by way of the event_has_list cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related CustomLists objects
     */
    public function countCustomListss($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collCustomListss || null !== $criteria) {
            if ($this->isNew() && null === $this->collCustomListss) {
                return 0;
            } else {
                $query = CustomListsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvents($this)
                    ->count($con);
            }
        } else {
            return count($this->collCustomListss);
        }
    }

    /**
     * Associate a CustomLists object to this object
     * through the event_has_list cross reference table.
     *
     * @param  CustomLists $customLists The EventHasList object to relate
     * @return Events The current object (for fluent API support)
     */
    public function addCustomLists(CustomLists $customLists)
    {
        if ($this->collCustomListss === null) {
            $this->initCustomListss();
        }

        if (!$this->collCustomListss->contains($customLists)) { // only add it if the **same** object is not already associated
            $this->doAddCustomLists($customLists);
            $this->collCustomListss[] = $customLists;

            if ($this->customListssScheduledForDeletion and $this->customListssScheduledForDeletion->contains($customLists)) {
                $this->customListssScheduledForDeletion->remove($this->customListssScheduledForDeletion->search($customLists));
            }
        }

        return $this;
    }

    /**
     * @param	CustomLists $customLists The customLists object to add.
     */
    protected function doAddCustomLists(CustomLists $customLists)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$customLists->getEventss()->contains($this)) { $eventHasList = new EventHasList();
            $eventHasList->setCustomLists($customLists);
            $this->addEventHasList($eventHasList);

            $foreignCollection = $customLists->getEventss();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a CustomLists object to this object
     * through the event_has_list cross reference table.
     *
     * @param CustomLists $customLists The EventHasList object to relate
     * @return Events The current object (for fluent API support)
     */
    public function removeCustomLists(CustomLists $customLists)
    {
        if ($this->getCustomListss()->contains($customLists)) {
            $this->collCustomListss->remove($this->collCustomListss->search($customLists));
            if (null === $this->customListssScheduledForDeletion) {
                $this->customListssScheduledForDeletion = clone $this->collCustomListss;
                $this->customListssScheduledForDeletion->clear();
            }
            $this->customListssScheduledForDeletion[]= $customLists;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id_event = null;
        $this->event_name = null;
        $this->event_description = null;
        $this->event_weight = null;
        $this->event_date = null;
        $this->event_order = null;
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
            if ($this->collEventHasLists) {
                foreach ($this->collEventHasLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCustomListss) {
                foreach ($this->collCustomListss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collEventHasLists instanceof PropelCollection) {
            $this->collEventHasLists->clearIterator();
        }
        $this->collEventHasLists = null;
        if ($this->collCustomListss instanceof PropelCollection) {
            $this->collCustomListss->clearIterator();
        }
        $this->collCustomListss = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventsPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Events The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = EventsPeer::UPDATED_AT;

        return $this;
    }

}
