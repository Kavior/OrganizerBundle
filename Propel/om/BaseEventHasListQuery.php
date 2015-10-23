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
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\EventHasList;
use Org\CoreBundle\Propel\EventHasListPeer;
use Org\CoreBundle\Propel\EventHasListQuery;
use Org\CoreBundle\Propel\Events;

/**
 * @method EventHasListQuery orderByIdEvent($order = Criteria::ASC) Order by the id_event column
 * @method EventHasListQuery orderByIdList($order = Criteria::ASC) Order by the id_list column
 *
 * @method EventHasListQuery groupByIdEvent() Group by the id_event column
 * @method EventHasListQuery groupByIdList() Group by the id_list column
 *
 * @method EventHasListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventHasListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventHasListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventHasListQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method EventHasListQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method EventHasListQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method EventHasListQuery leftJoinCustomLists($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomLists relation
 * @method EventHasListQuery rightJoinCustomLists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomLists relation
 * @method EventHasListQuery innerJoinCustomLists($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomLists relation
 *
 * @method EventHasList findOne(PropelPDO $con = null) Return the first EventHasList matching the query
 * @method EventHasList findOneOrCreate(PropelPDO $con = null) Return the first EventHasList matching the query, or a new EventHasList object populated from the query conditions when no match is found
 *
 * @method EventHasList findOneByIdEvent(int $id_event) Return the first EventHasList filtered by the id_event column
 * @method EventHasList findOneByIdList(int $id_list) Return the first EventHasList filtered by the id_list column
 *
 * @method array findByIdEvent(int $id_event) Return EventHasList objects filtered by the id_event column
 * @method array findByIdList(int $id_list) Return EventHasList objects filtered by the id_list column
 */
abstract class BaseEventHasListQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventHasListQuery object.
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
            $modelName = 'Org\\CoreBundle\\Propel\\EventHasList';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventHasListQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EventHasListQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventHasListQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventHasListQuery) {
            return $criteria;
        }
        $query = new EventHasListQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$id_event, $id_list]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   EventHasList|EventHasList[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventHasListPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 EventHasList A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_event`, `id_list` FROM `event_has_list` WHERE `id_event` = :p0 AND `id_list` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new EventHasList();
            $obj->hydrate($row);
            EventHasListPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return EventHasList|EventHasList[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|EventHasList[]|mixed the list of results, formatted by the current formatter
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
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(EventHasListPeer::ID_EVENT, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(EventHasListPeer::ID_LIST, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(EventHasListPeer::ID_EVENT, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(EventHasListPeer::ID_LIST, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByEvents()
     *
     * @param     mixed $idEvent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function filterByIdEvent($idEvent = null, $comparison = null)
    {
        if (is_array($idEvent)) {
            $useMinMax = false;
            if (isset($idEvent['min'])) {
                $this->addUsingAlias(EventHasListPeer::ID_EVENT, $idEvent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idEvent['max'])) {
                $this->addUsingAlias(EventHasListPeer::ID_EVENT, $idEvent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventHasListPeer::ID_EVENT, $idEvent, $comparison);
    }

    /**
     * Filter the query on the id_list column
     *
     * Example usage:
     * <code>
     * $query->filterByIdList(1234); // WHERE id_list = 1234
     * $query->filterByIdList(array(12, 34)); // WHERE id_list IN (12, 34)
     * $query->filterByIdList(array('min' => 12)); // WHERE id_list >= 12
     * $query->filterByIdList(array('max' => 12)); // WHERE id_list <= 12
     * </code>
     *
     * @see       filterByCustomLists()
     *
     * @param     mixed $idList The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function filterByIdList($idList = null, $comparison = null)
    {
        if (is_array($idList)) {
            $useMinMax = false;
            if (isset($idList['min'])) {
                $this->addUsingAlias(EventHasListPeer::ID_LIST, $idList['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idList['max'])) {
                $this->addUsingAlias(EventHasListPeer::ID_LIST, $idList['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventHasListPeer::ID_LIST, $idList, $comparison);
    }

    /**
     * Filter the query by a related Events object
     *
     * @param   Events|PropelObjectCollection $events The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventHasListQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof Events) {
            return $this
                ->addUsingAlias(EventHasListPeer::ID_EVENT, $events->getIdEvent(), $comparison);
        } elseif ($events instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventHasListPeer::ID_EVENT, $events->toKeyValue('PrimaryKey', 'IdEvent'), $comparison);
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type Events or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function joinEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Events');

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
            $this->addJoinObject($join, 'Events');
        }

        return $this;
    }

    /**
     * Use the Events relation Events object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\Org\CoreBundle\Propel\EventsQuery');
    }

    /**
     * Filter the query by a related CustomLists object
     *
     * @param   CustomLists|PropelObjectCollection $customLists The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventHasListQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCustomLists($customLists, $comparison = null)
    {
        if ($customLists instanceof CustomLists) {
            return $this
                ->addUsingAlias(EventHasListPeer::ID_LIST, $customLists->getIdCustomList(), $comparison);
        } elseif ($customLists instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventHasListPeer::ID_LIST, $customLists->toKeyValue('PrimaryKey', 'IdCustomList'), $comparison);
        } else {
            throw new PropelException('filterByCustomLists() only accepts arguments of type CustomLists or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomLists relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function joinCustomLists($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomLists');

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
            $this->addJoinObject($join, 'CustomLists');
        }

        return $this;
    }

    /**
     * Use the CustomLists relation CustomLists object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\CustomListsQuery A secondary query class using the current class as primary query
     */
    public function useCustomListsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomLists($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomLists', '\Org\CoreBundle\Propel\CustomListsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EventHasList $eventHasList Object to remove from the list of results
     *
     * @return EventHasListQuery The current query, for fluid interface
     */
    public function prune($eventHasList = null)
    {
        if ($eventHasList) {
            $this->addCond('pruneCond0', $this->getAliasedColName(EventHasListPeer::ID_EVENT), $eventHasList->getIdEvent(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(EventHasListPeer::ID_LIST), $eventHasList->getIdList(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
