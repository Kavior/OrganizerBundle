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
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\CyclicalEventsPeer;
use Org\CoreBundle\Propel\CyclicalEventsQuery;

/**
 * @method CyclicalEventsQuery orderByIdCyclicalEvent($order = Criteria::ASC) Order by the id_cyclical_event column
 * @method CyclicalEventsQuery orderByCyclicalEventName($order = Criteria::ASC) Order by the cyclical_event_name column
 * @method CyclicalEventsQuery orderByCyclicalEventDescription($order = Criteria::ASC) Order by the cyclical_event_description column
 * @method CyclicalEventsQuery orderByCyclicalEventWeight($order = Criteria::ASC) Order by the cyclical_event_weight column
 * @method CyclicalEventsQuery orderByCyclicalEventMonth($order = Criteria::ASC) Order by the cyclical_event_month column
 * @method CyclicalEventsQuery orderByCyclicalEventWeekDay($order = Criteria::ASC) Order by the cyclical_event_week_day column
 * @method CyclicalEventsQuery orderByCyclicalEventDay($order = Criteria::ASC) Order by the cyclical_event_day column
 * @method CyclicalEventsQuery orderByIdUser($order = Criteria::ASC) Order by the id_user column
 * @method CyclicalEventsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CyclicalEventsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CyclicalEventsQuery groupByIdCyclicalEvent() Group by the id_cyclical_event column
 * @method CyclicalEventsQuery groupByCyclicalEventName() Group by the cyclical_event_name column
 * @method CyclicalEventsQuery groupByCyclicalEventDescription() Group by the cyclical_event_description column
 * @method CyclicalEventsQuery groupByCyclicalEventWeight() Group by the cyclical_event_weight column
 * @method CyclicalEventsQuery groupByCyclicalEventMonth() Group by the cyclical_event_month column
 * @method CyclicalEventsQuery groupByCyclicalEventWeekDay() Group by the cyclical_event_week_day column
 * @method CyclicalEventsQuery groupByCyclicalEventDay() Group by the cyclical_event_day column
 * @method CyclicalEventsQuery groupByIdUser() Group by the id_user column
 * @method CyclicalEventsQuery groupByCreatedAt() Group by the created_at column
 * @method CyclicalEventsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CyclicalEventsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CyclicalEventsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CyclicalEventsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CyclicalEventsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method CyclicalEventsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method CyclicalEventsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method CyclicalEventsQuery leftJoinCyclicalEventHasList($relationAlias = null) Adds a LEFT JOIN clause to the query using the CyclicalEventHasList relation
 * @method CyclicalEventsQuery rightJoinCyclicalEventHasList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CyclicalEventHasList relation
 * @method CyclicalEventsQuery innerJoinCyclicalEventHasList($relationAlias = null) Adds a INNER JOIN clause to the query using the CyclicalEventHasList relation
 *
 * @method CyclicalEvents findOne(PropelPDO $con = null) Return the first CyclicalEvents matching the query
 * @method CyclicalEvents findOneOrCreate(PropelPDO $con = null) Return the first CyclicalEvents matching the query, or a new CyclicalEvents object populated from the query conditions when no match is found
 *
 * @method CyclicalEvents findOneByCyclicalEventName(string $cyclical_event_name) Return the first CyclicalEvents filtered by the cyclical_event_name column
 * @method CyclicalEvents findOneByCyclicalEventDescription(string $cyclical_event_description) Return the first CyclicalEvents filtered by the cyclical_event_description column
 * @method CyclicalEvents findOneByCyclicalEventWeight(string $cyclical_event_weight) Return the first CyclicalEvents filtered by the cyclical_event_weight column
 * @method CyclicalEvents findOneByCyclicalEventMonth(string $cyclical_event_month) Return the first CyclicalEvents filtered by the cyclical_event_month column
 * @method CyclicalEvents findOneByCyclicalEventWeekDay(string $cyclical_event_week_day) Return the first CyclicalEvents filtered by the cyclical_event_week_day column
 * @method CyclicalEvents findOneByCyclicalEventDay(string $cyclical_event_day) Return the first CyclicalEvents filtered by the cyclical_event_day column
 * @method CyclicalEvents findOneByIdUser(int $id_user) Return the first CyclicalEvents filtered by the id_user column
 * @method CyclicalEvents findOneByCreatedAt(string $created_at) Return the first CyclicalEvents filtered by the created_at column
 * @method CyclicalEvents findOneByUpdatedAt(string $updated_at) Return the first CyclicalEvents filtered by the updated_at column
 *
 * @method array findByIdCyclicalEvent(int $id_cyclical_event) Return CyclicalEvents objects filtered by the id_cyclical_event column
 * @method array findByCyclicalEventName(string $cyclical_event_name) Return CyclicalEvents objects filtered by the cyclical_event_name column
 * @method array findByCyclicalEventDescription(string $cyclical_event_description) Return CyclicalEvents objects filtered by the cyclical_event_description column
 * @method array findByCyclicalEventWeight(string $cyclical_event_weight) Return CyclicalEvents objects filtered by the cyclical_event_weight column
 * @method array findByCyclicalEventMonth(string $cyclical_event_month) Return CyclicalEvents objects filtered by the cyclical_event_month column
 * @method array findByCyclicalEventWeekDay(string $cyclical_event_week_day) Return CyclicalEvents objects filtered by the cyclical_event_week_day column
 * @method array findByCyclicalEventDay(string $cyclical_event_day) Return CyclicalEvents objects filtered by the cyclical_event_day column
 * @method array findByIdUser(int $id_user) Return CyclicalEvents objects filtered by the id_user column
 * @method array findByCreatedAt(string $created_at) Return CyclicalEvents objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CyclicalEvents objects filtered by the updated_at column
 */
abstract class BaseCyclicalEventsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCyclicalEventsQuery object.
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
            $modelName = 'Org\\CoreBundle\\Propel\\CyclicalEvents';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CyclicalEventsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CyclicalEventsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CyclicalEventsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CyclicalEventsQuery) {
            return $criteria;
        }
        $query = new CyclicalEventsQuery(null, null, $modelAlias);

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
     * @return   CyclicalEvents|CyclicalEvents[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CyclicalEventsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CyclicalEvents A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByIdCyclicalEvent($key, $con = null)
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
     * @return                 CyclicalEvents A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_cyclical_event`, `cyclical_event_name`, `cyclical_event_description`, `cyclical_event_weight`, `cyclical_event_month`, `cyclical_event_week_day`, `cyclical_event_day`, `id_user`, `created_at`, `updated_at` FROM `cyclical_events` WHERE `id_cyclical_event` = :p0';
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
            $obj = new CyclicalEvents();
            $obj->hydrate($row);
            CyclicalEventsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CyclicalEvents|CyclicalEvents[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CyclicalEvents[]|mixed the list of results, formatted by the current formatter
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
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_cyclical_event column
     *
     * Example usage:
     * <code>
     * $query->filterByIdCyclicalEvent(1234); // WHERE id_cyclical_event = 1234
     * $query->filterByIdCyclicalEvent(array(12, 34)); // WHERE id_cyclical_event IN (12, 34)
     * $query->filterByIdCyclicalEvent(array('min' => 12)); // WHERE id_cyclical_event >= 12
     * $query->filterByIdCyclicalEvent(array('max' => 12)); // WHERE id_cyclical_event <= 12
     * </code>
     *
     * @param     mixed $idCyclicalEvent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByIdCyclicalEvent($idCyclicalEvent = null, $comparison = null)
    {
        if (is_array($idCyclicalEvent)) {
            $useMinMax = false;
            if (isset($idCyclicalEvent['min'])) {
                $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idCyclicalEvent['max'])) {
                $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_name column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventName('fooValue');   // WHERE cyclical_event_name = 'fooValue'
     * $query->filterByCyclicalEventName('%fooValue%'); // WHERE cyclical_event_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventName($cyclicalEventName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventName)) {
                $cyclicalEventName = str_replace('*', '%', $cyclicalEventName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_NAME, $cyclicalEventName, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_description column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventDescription('fooValue');   // WHERE cyclical_event_description = 'fooValue'
     * $query->filterByCyclicalEventDescription('%fooValue%'); // WHERE cyclical_event_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventDescription($cyclicalEventDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventDescription)) {
                $cyclicalEventDescription = str_replace('*', '%', $cyclicalEventDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_DESCRIPTION, $cyclicalEventDescription, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventWeight('fooValue');   // WHERE cyclical_event_weight = 'fooValue'
     * $query->filterByCyclicalEventWeight('%fooValue%'); // WHERE cyclical_event_weight LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventWeight The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventWeight($cyclicalEventWeight = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventWeight)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventWeight)) {
                $cyclicalEventWeight = str_replace('*', '%', $cyclicalEventWeight);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_WEIGHT, $cyclicalEventWeight, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_month column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventMonth('fooValue');   // WHERE cyclical_event_month = 'fooValue'
     * $query->filterByCyclicalEventMonth('%fooValue%'); // WHERE cyclical_event_month LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventMonth The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventMonth($cyclicalEventMonth = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventMonth)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventMonth)) {
                $cyclicalEventMonth = str_replace('*', '%', $cyclicalEventMonth);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_MONTH, $cyclicalEventMonth, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_week_day column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventWeekDay('fooValue');   // WHERE cyclical_event_week_day = 'fooValue'
     * $query->filterByCyclicalEventWeekDay('%fooValue%'); // WHERE cyclical_event_week_day LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventWeekDay The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventWeekDay($cyclicalEventWeekDay = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventWeekDay)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventWeekDay)) {
                $cyclicalEventWeekDay = str_replace('*', '%', $cyclicalEventWeekDay);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_WEEK_DAY, $cyclicalEventWeekDay, $comparison);
    }

    /**
     * Filter the query on the cyclical_event_day column
     *
     * Example usage:
     * <code>
     * $query->filterByCyclicalEventDay('fooValue');   // WHERE cyclical_event_day = 'fooValue'
     * $query->filterByCyclicalEventDay('%fooValue%'); // WHERE cyclical_event_day LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cyclicalEventDay The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEventDay($cyclicalEventDay = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cyclicalEventDay)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cyclicalEventDay)) {
                $cyclicalEventDay = str_replace('*', '%', $cyclicalEventDay);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CYCLICAL_EVENT_DAY, $cyclicalEventDay, $comparison);
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
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(CyclicalEventsPeer::ID_USER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(CyclicalEventsPeer::ID_USER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::ID_USER, $idUser, $comparison);
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
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CyclicalEventsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CyclicalEventsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CyclicalEventsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CyclicalEventsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CyclicalEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(CyclicalEventsPeer::ID_USER, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CyclicalEventsPeer::ID_USER, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CyclicalEventsQuery The current query, for fluid interface
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
     * Filter the query by a related CyclicalEventHasList object
     *
     * @param   CyclicalEventHasList|PropelObjectCollection $cyclicalEventHasList  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CyclicalEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCyclicalEventHasList($cyclicalEventHasList, $comparison = null)
    {
        if ($cyclicalEventHasList instanceof CyclicalEventHasList) {
            return $this
                ->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $cyclicalEventHasList->getIdCyclicalEvent(), $comparison);
        } elseif ($cyclicalEventHasList instanceof PropelObjectCollection) {
            return $this
                ->useCyclicalEventHasListQuery()
                ->filterByPrimaryKeys($cyclicalEventHasList->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCyclicalEventHasList() only accepts arguments of type CyclicalEventHasList or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CyclicalEventHasList relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function joinCyclicalEventHasList($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CyclicalEventHasList');

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
            $this->addJoinObject($join, 'CyclicalEventHasList');
        }

        return $this;
    }

    /**
     * Use the CyclicalEventHasList relation CyclicalEventHasList object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\CyclicalEventHasListQuery A secondary query class using the current class as primary query
     */
    public function useCyclicalEventHasListQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCyclicalEventHasList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CyclicalEventHasList', '\Org\CoreBundle\Propel\CyclicalEventHasListQuery');
    }

    /**
     * Filter the query by a related CustomLists object
     * using the cyclical_event_has_list table as cross reference
     *
     * @param   CustomLists $customLists the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CyclicalEventsQuery The current query, for fluid interface
     */
    public function filterByCustomLists($customLists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCyclicalEventHasListQuery()
            ->filterByCustomLists($customLists, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   CyclicalEvents $cyclicalEvents Object to remove from the list of results
     *
     * @return CyclicalEventsQuery The current query, for fluid interface
     */
    public function prune($cyclicalEvents = null)
    {
        if ($cyclicalEvents) {
            $this->addUsingAlias(CyclicalEventsPeer::ID_CYCLICAL_EVENT, $cyclicalEvents->getIdCyclicalEvent(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CyclicalEventsPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CyclicalEventsPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CyclicalEventsPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CyclicalEventsPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CyclicalEventsPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CyclicalEventsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CyclicalEventsPeer::CREATED_AT);
    }
}
