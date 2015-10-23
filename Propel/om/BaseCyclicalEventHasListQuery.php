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
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEventHasListPeer;
use Org\CoreBundle\Propel\CyclicalEventHasListQuery;
use Org\CoreBundle\Propel\CyclicalEvents;

/**
 * @method CyclicalEventHasListQuery orderByIdCyclicalEvent($order = Criteria::ASC) Order by the id_cyclical_event column
 * @method CyclicalEventHasListQuery orderByIdList($order = Criteria::ASC) Order by the id_list column
 *
 * @method CyclicalEventHasListQuery groupByIdCyclicalEvent() Group by the id_cyclical_event column
 * @method CyclicalEventHasListQuery groupByIdList() Group by the id_list column
 *
 * @method CyclicalEventHasListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CyclicalEventHasListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CyclicalEventHasListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CyclicalEventHasListQuery leftJoinCyclicalEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the CyclicalEvents relation
 * @method CyclicalEventHasListQuery rightJoinCyclicalEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CyclicalEvents relation
 * @method CyclicalEventHasListQuery innerJoinCyclicalEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the CyclicalEvents relation
 *
 * @method CyclicalEventHasListQuery leftJoinCustomLists($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomLists relation
 * @method CyclicalEventHasListQuery rightJoinCustomLists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomLists relation
 * @method CyclicalEventHasListQuery innerJoinCustomLists($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomLists relation
 *
 * @method CyclicalEventHasList findOne(PropelPDO $con = null) Return the first CyclicalEventHasList matching the query
 * @method CyclicalEventHasList findOneOrCreate(PropelPDO $con = null) Return the first CyclicalEventHasList matching the query, or a new CyclicalEventHasList object populated from the query conditions when no match is found
 *
 * @method CyclicalEventHasList findOneByIdCyclicalEvent(int $id_cyclical_event) Return the first CyclicalEventHasList filtered by the id_cyclical_event column
 * @method CyclicalEventHasList findOneByIdList(int $id_list) Return the first CyclicalEventHasList filtered by the id_list column
 *
 * @method array findByIdCyclicalEvent(int $id_cyclical_event) Return CyclicalEventHasList objects filtered by the id_cyclical_event column
 * @method array findByIdList(int $id_list) Return CyclicalEventHasList objects filtered by the id_list column
 */
abstract class BaseCyclicalEventHasListQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCyclicalEventHasListQuery object.
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
            $modelName = 'Org\\CoreBundle\\Propel\\CyclicalEventHasList';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CyclicalEventHasListQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CyclicalEventHasListQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CyclicalEventHasListQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CyclicalEventHasListQuery) {
            return $criteria;
        }
        $query = new CyclicalEventHasListQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$id_cyclical_event, $id_list]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CyclicalEventHasList|CyclicalEventHasList[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CyclicalEventHasListPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CyclicalEventHasListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CyclicalEventHasList A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_cyclical_event`, `id_list` FROM `cyclical_event_has_list` WHERE `id_cyclical_event` = :p0 AND `id_list` = :p1';
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
            $obj = new CyclicalEventHasList();
            $obj->hydrate($row);
            CyclicalEventHasListPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return CyclicalEventHasList|CyclicalEventHasList[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CyclicalEventHasList[]|mixed the list of results, formatted by the current formatter
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
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CyclicalEventHasListPeer::ID_LIST, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByCyclicalEvents()
     *
     * @param     mixed $idCyclicalEvent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function filterByIdCyclicalEvent($idCyclicalEvent = null, $comparison = null)
    {
        if (is_array($idCyclicalEvent)) {
            $useMinMax = false;
            if (isset($idCyclicalEvent['min'])) {
                $this->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idCyclicalEvent['max'])) {
                $this->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $idCyclicalEvent, $comparison);
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
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function filterByIdList($idList = null, $comparison = null)
    {
        if (is_array($idList)) {
            $useMinMax = false;
            if (isset($idList['min'])) {
                $this->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $idList['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idList['max'])) {
                $this->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $idList['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $idList, $comparison);
    }

    /**
     * Filter the query by a related CyclicalEvents object
     *
     * @param   CyclicalEvents|PropelObjectCollection $cyclicalEvents The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CyclicalEventHasListQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCyclicalEvents($cyclicalEvents, $comparison = null)
    {
        if ($cyclicalEvents instanceof CyclicalEvents) {
            return $this
                ->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $cyclicalEvents->getIdCyclicalEvent(), $comparison);
        } elseif ($cyclicalEvents instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT, $cyclicalEvents->toKeyValue('PrimaryKey', 'IdCyclicalEvent'), $comparison);
        } else {
            throw new PropelException('filterByCyclicalEvents() only accepts arguments of type CyclicalEvents or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CyclicalEvents relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function joinCyclicalEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CyclicalEvents');

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
            $this->addJoinObject($join, 'CyclicalEvents');
        }

        return $this;
    }

    /**
     * Use the CyclicalEvents relation CyclicalEvents object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\CyclicalEventsQuery A secondary query class using the current class as primary query
     */
    public function useCyclicalEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCyclicalEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CyclicalEvents', '\Org\CoreBundle\Propel\CyclicalEventsQuery');
    }

    /**
     * Filter the query by a related CustomLists object
     *
     * @param   CustomLists|PropelObjectCollection $customLists The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CyclicalEventHasListQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCustomLists($customLists, $comparison = null)
    {
        if ($customLists instanceof CustomLists) {
            return $this
                ->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $customLists->getIdCustomList(), $comparison);
        } elseif ($customLists instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CyclicalEventHasListPeer::ID_LIST, $customLists->toKeyValue('PrimaryKey', 'IdCustomList'), $comparison);
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
     * @return CyclicalEventHasListQuery The current query, for fluid interface
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
     * @param   CyclicalEventHasList $cyclicalEventHasList Object to remove from the list of results
     *
     * @return CyclicalEventHasListQuery The current query, for fluid interface
     */
    public function prune($cyclicalEventHasList = null)
    {
        if ($cyclicalEventHasList) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CyclicalEventHasListPeer::ID_CYCLICAL_EVENT), $cyclicalEventHasList->getIdCyclicalEvent(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CyclicalEventHasListPeer::ID_LIST), $cyclicalEventHasList->getIdList(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
