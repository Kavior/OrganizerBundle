<?php

namespace Org\CoreBundle\Propel\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\EventHasList;
use Org\CoreBundle\Propel\Events;
use Org\CoreBundle\Propel\EventsPeer;
use Org\CoreBundle\Propel\EventsQuery;

/**
 * @method EventsQuery orderByIdEvent($order = Criteria::ASC) Order by the id_event column
 * @method EventsQuery orderByEventName($order = Criteria::ASC) Order by the event_name column
 * @method EventsQuery orderByEventDescription($order = Criteria::ASC) Order by the event_description column
 * @method EventsQuery orderByEventWeight($order = Criteria::ASC) Order by the event_weight column
 * @method EventsQuery orderByEventDate($order = Criteria::ASC) Order by the event_date column
 * @method EventsQuery orderByEventOrder($order = Criteria::ASC) Order by the event_order column
 * @method EventsQuery orderByIdUser($order = Criteria::ASC) Order by the id_user column
 * @method EventsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method EventsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method EventsQuery groupByIdEvent() Group by the id_event column
 * @method EventsQuery groupByEventName() Group by the event_name column
 * @method EventsQuery groupByEventDescription() Group by the event_description column
 * @method EventsQuery groupByEventWeight() Group by the event_weight column
 * @method EventsQuery groupByEventDate() Group by the event_date column
 * @method EventsQuery groupByEventOrder() Group by the event_order column
 * @method EventsQuery groupByIdUser() Group by the id_user column
 * @method EventsQuery groupByCreatedAt() Group by the created_at column
 * @method EventsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method EventsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method EventsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method EventsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method EventsQuery leftJoinEventHasList($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventHasList relation
 * @method EventsQuery rightJoinEventHasList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventHasList relation
 * @method EventsQuery innerJoinEventHasList($relationAlias = null) Adds a INNER JOIN clause to the query using the EventHasList relation
 *
 * @method Events findOne(PropelPDO $con = null) Return the first Events matching the query
 * @method Events findOneOrCreate(PropelPDO $con = null) Return the first Events matching the query, or a new Events object populated from the query conditions when no match is found
 *
 * @method Events findOneByEventName(string $event_name) Return the first Events filtered by the event_name column
 * @method Events findOneByEventDescription(string $event_description) Return the first Events filtered by the event_description column
 * @method Events findOneByEventWeight(int $event_weight) Return the first Events filtered by the event_weight column
 * @method Events findOneByEventDate(string $event_date) Return the first Events filtered by the event_date column
 * @method Events findOneByEventOrder(int $event_order) Return the first Events filtered by the event_order column
 * @method Events findOneByIdUser(int $id_user) Return the first Events filtered by the id_user column
 * @method Events findOneByCreatedAt(string $created_at) Return the first Events filtered by the created_at column
 * @method Events findOneByUpdatedAt(string $updated_at) Return the first Events filtered by the updated_at column
 *
 * @method array findByIdEvent(int $id_event) Return Events objects filtered by the id_event column
 * @method array findByEventName(string $event_name) Return Events objects filtered by the event_name column
 * @method array findByEventDescription(string $event_description) Return Events objects filtered by the event_description column
 * @method array findByEventWeight(int $event_weight) Return Events objects filtered by the event_weight column
 * @method array findByEventDate(string $event_date) Return Events objects filtered by the event_date column
 * @method array findByEventOrder(int $event_order) Return Events objects filtered by the event_order column
 * @method array findByIdUser(int $id_user) Return Events objects filtered by the id_user column
 * @method array findByCreatedAt(string $created_at) Return Events objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Events objects filtered by the updated_at column
 */
abstract class BaseEventsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventsQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Org\\CoreBundle\\Propel\\Events';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EventsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventsQuery) {
            return $criteria;
        }
        $query = new EventsQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Events|Events[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Events A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByIdEvent($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Events A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_event`, `event_name`, `event_description`, `event_weight`, `event_date`, `event_order`, `id_user`, `created_at`, `updated_at` FROM `events` WHERE `id_event` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Events();
            $obj->hydrate($row);
            EventsPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Events|Events[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Events[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventsPeer::ID_EVENT, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventsPeer::ID_EVENT, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_event column
     *
     * Example usage:
     * <code>
     * $query->filterByIdEvent(1234); // WHERE id_event = 1234
     * $query->filterByIdEvent(array(12, 34)); // WHERE id_event IN (12, 34)
     * $query->filterByIdEvent(array('min' => 12)); // WHERE id_event >= 12
     * $query->filterByIdEvent(array('max' => 12)); // WHERE id_event <= 12
     * </code>
     *
     * @param     mixed $idEvent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByIdEvent($idEvent = null, $comparison = null)
    {
        if (is_array($idEvent)) {
            $useMinMax = false;
            if (isset($idEvent['min'])) {
                $this->addUsingAlias(EventsPeer::ID_EVENT, $idEvent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idEvent['max'])) {
                $this->addUsingAlias(EventsPeer::ID_EVENT, $idEvent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::ID_EVENT, $idEvent, $comparison);
    }

    /**
     * Filter the query on the event_name column
     *
     * Example usage:
     * <code>
     * $query->filterByEventName('fooValue');   // WHERE event_name = 'fooValue'
     * $query->filterByEventName('%fooValue%'); // WHERE event_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByEventName($eventName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventName)) {
                $eventName = str_replace('*', '%', $eventName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventsPeer::EVENT_NAME, $eventName, $comparison);
    }

    /**
     * Filter the query on the event_description column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDescription('fooValue');   // WHERE event_description = 'fooValue'
     * $query->filterByEventDescription('%fooValue%'); // WHERE event_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByEventDescription($eventDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventDescription)) {
                $eventDescription = str_replace('*', '%', $eventDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventsPeer::EVENT_DESCRIPTION, $eventDescription, $comparison);
    }

    /**
     * Filter the query on the event_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByEventWeight(1234); // WHERE event_weight = 1234
     * $query->filterByEventWeight(array(12, 34)); // WHERE event_weight IN (12, 34)
     * $query->filterByEventWeight(array('min' => 12)); // WHERE event_weight >= 12
     * $query->filterByEventWeight(array('max' => 12)); // WHERE event_weight <= 12
     * </code>
     *
     * @param     mixed $eventWeight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByEventWeight($eventWeight = null, $comparison = null)
    {
        if (is_array($eventWeight)) {
            $useMinMax = false;
            if (isset($eventWeight['min'])) {
                $this->addUsingAlias(EventsPeer::EVENT_WEIGHT, $eventWeight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventWeight['max'])) {
                $this->addUsingAlias(EventsPeer::EVENT_WEIGHT, $eventWeight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::EVENT_WEIGHT, $eventWeight, $comparison);
    }

    /**
     * Filter the query on the event_date column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDate('fooValue');   // WHERE event_date = 'fooValue'
     * $query->filterByEventDate('%fooValue%'); // WHERE event_date LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventDate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByEventDate($eventDate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventDate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventDate)) {
                $eventDate = str_replace('*', '%', $eventDate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventsPeer::EVENT_DATE, $eventDate, $comparison);
    }

    /**
     * Filter the query on the event_order column
     *
     * Example usage:
     * <code>
     * $query->filterByEventOrder(1234); // WHERE event_order = 1234
     * $query->filterByEventOrder(array(12, 34)); // WHERE event_order IN (12, 34)
     * $query->filterByEventOrder(array('min' => 12)); // WHERE event_order >= 12
     * $query->filterByEventOrder(array('max' => 12)); // WHERE event_order <= 12
     * </code>
     *
     * @param     mixed $eventOrder The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByEventOrder($eventOrder = null, $comparison = null)
    {
        if (is_array($eventOrder)) {
            $useMinMax = false;
            if (isset($eventOrder['min'])) {
                $this->addUsingAlias(EventsPeer::EVENT_ORDER, $eventOrder['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventOrder['max'])) {
                $this->addUsingAlias(EventsPeer::EVENT_ORDER, $eventOrder['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::EVENT_ORDER, $eventOrder, $comparison);
    }

    /**
     * Filter the query on the id_user column
     *
     * Example usage:
     * <code>
     * $query->filterByIdUser(1234); // WHERE id_user = 1234
     * $query->filterByIdUser(array(12, 34)); // WHERE id_user IN (12, 34)
     * $query->filterByIdUser(array('min' => 12)); // WHERE id_user >= 12
     * $query->filterByIdUser(array('max' => 12)); // WHERE id_user <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $idUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(EventsPeer::ID_USER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(EventsPeer::ID_USER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::ID_USER, $idUser, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EventsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EventsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EventsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EventsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(EventsPeer::ID_USER, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventsPeer::ID_USER, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related EventHasList object
     *
     * @param   EventHasList|PropelObjectCollection $eventHasList  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventHasList($eventHasList, $comparison = null)
    {
        if ($eventHasList instanceof EventHasList) {
            return $this
                ->addUsingAlias(EventsPeer::ID_EVENT, $eventHasList->getIdEvent(), $comparison);
        } elseif ($eventHasList instanceof PropelObjectCollection) {
            return $this
                ->useEventHasListQuery()
                ->filterByPrimaryKeys($eventHasList->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventHasList() only accepts arguments of type EventHasList or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventHasList relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function joinEventHasList($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventHasList');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EventHasList');
        }

        return $this;
    }

    /**
     * Use the EventHasList relation EventHasList object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\EventHasListQuery A secondary query class using the current class as primary query
     */
    public function useEventHasListQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventHasList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventHasList', '\Org\CoreBundle\Propel\EventHasListQuery');
    }

    /**
     * Filter the query by a related CustomLists object
     * using the event_has_list table as cross reference
     *
     * @param   CustomLists $customLists the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventsQuery The current query, for fluid interface
     */
    public function filterByCustomLists($customLists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useEventHasListQuery()
            ->filterByCustomLists($customLists, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Events $events Object to remove from the list of results
     *
     * @return EventsQuery The current query, for fluid interface
     */
    public function prune($events = null)
    {
        if ($events) {
            $this->addUsingAlias(EventsPeer::ID_EVENT, $events->getIdEvent(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EventsPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventsPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventsPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EventsPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventsPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     EventsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventsPeer::CREATED_AT);
    }
}
