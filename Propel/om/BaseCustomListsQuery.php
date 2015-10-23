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
use Org\CoreBundle\Propel\CustomListElement;
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListsPeer;
use Org\CoreBundle\Propel\CustomListsQuery;
use Org\CoreBundle\Propel\CyclicalEventHasList;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\EventHasList;
use Org\CoreBundle\Propel\Events;

/**
 * @method CustomListsQuery orderByIdCustomList($order = Criteria::ASC) Order by the id_custom_list column
 * @method CustomListsQuery orderByListName($order = Criteria::ASC) Order by the list_name column
 * @method CustomListsQuery orderByIdUser($order = Criteria::ASC) Order by the id_user column
 * @method CustomListsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CustomListsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CustomListsQuery groupByIdCustomList() Group by the id_custom_list column
 * @method CustomListsQuery groupByListName() Group by the list_name column
 * @method CustomListsQuery groupByIdUser() Group by the id_user column
 * @method CustomListsQuery groupByCreatedAt() Group by the created_at column
 * @method CustomListsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CustomListsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CustomListsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CustomListsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CustomListsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method CustomListsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method CustomListsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method CustomListsQuery leftJoinCustomListElement($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomListElement relation
 * @method CustomListsQuery rightJoinCustomListElement($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomListElement relation
 * @method CustomListsQuery innerJoinCustomListElement($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomListElement relation
 *
 * @method CustomListsQuery leftJoinCyclicalEventHasList($relationAlias = null) Adds a LEFT JOIN clause to the query using the CyclicalEventHasList relation
 * @method CustomListsQuery rightJoinCyclicalEventHasList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CyclicalEventHasList relation
 * @method CustomListsQuery innerJoinCyclicalEventHasList($relationAlias = null) Adds a INNER JOIN clause to the query using the CyclicalEventHasList relation
 *
 * @method CustomListsQuery leftJoinEventHasList($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventHasList relation
 * @method CustomListsQuery rightJoinEventHasList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventHasList relation
 * @method CustomListsQuery innerJoinEventHasList($relationAlias = null) Adds a INNER JOIN clause to the query using the EventHasList relation
 *
 * @method CustomLists findOne(PropelPDO $con = null) Return the first CustomLists matching the query
 * @method CustomLists findOneOrCreate(PropelPDO $con = null) Return the first CustomLists matching the query, or a new CustomLists object populated from the query conditions when no match is found
 *
 * @method CustomLists findOneByListName(string $list_name) Return the first CustomLists filtered by the list_name column
 * @method CustomLists findOneByIdUser(int $id_user) Return the first CustomLists filtered by the id_user column
 * @method CustomLists findOneByCreatedAt(string $created_at) Return the first CustomLists filtered by the created_at column
 * @method CustomLists findOneByUpdatedAt(string $updated_at) Return the first CustomLists filtered by the updated_at column
 *
 * @method array findByIdCustomList(int $id_custom_list) Return CustomLists objects filtered by the id_custom_list column
 * @method array findByListName(string $list_name) Return CustomLists objects filtered by the list_name column
 * @method array findByIdUser(int $id_user) Return CustomLists objects filtered by the id_user column
 * @method array findByCreatedAt(string $created_at) Return CustomLists objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CustomLists objects filtered by the updated_at column
 */
abstract class BaseCustomListsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCustomListsQuery object.
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
            $modelName = 'Org\\CoreBundle\\Propel\\CustomLists';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CustomListsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CustomListsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CustomListsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CustomListsQuery) {
            return $criteria;
        }
        $query = new CustomListsQuery(null, null, $modelAlias);

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
     * @return   CustomLists|CustomLists[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomListsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CustomListsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CustomLists A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByIdCustomList($key, $con = null)
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
     * @return                 CustomLists A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_custom_list`, `list_name`, `id_user`, `created_at`, `updated_at` FROM `custom_lists` WHERE `id_custom_list` = :p0';
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
            $obj = new CustomLists();
            $obj->hydrate($row);
            CustomListsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CustomLists|CustomLists[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CustomLists[]|mixed the list of results, formatted by the current formatter
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
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_custom_list column
     *
     * Example usage:
     * <code>
     * $query->filterByIdCustomList(1234); // WHERE id_custom_list = 1234
     * $query->filterByIdCustomList(array(12, 34)); // WHERE id_custom_list IN (12, 34)
     * $query->filterByIdCustomList(array('min' => 12)); // WHERE id_custom_list >= 12
     * $query->filterByIdCustomList(array('max' => 12)); // WHERE id_custom_list <= 12
     * </code>
     *
     * @param     mixed $idCustomList The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByIdCustomList($idCustomList = null, $comparison = null)
    {
        if (is_array($idCustomList)) {
            $useMinMax = false;
            if (isset($idCustomList['min'])) {
                $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $idCustomList['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idCustomList['max'])) {
                $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $idCustomList['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $idCustomList, $comparison);
    }

    /**
     * Filter the query on the list_name column
     *
     * Example usage:
     * <code>
     * $query->filterByListName('fooValue');   // WHERE list_name = 'fooValue'
     * $query->filterByListName('%fooValue%'); // WHERE list_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $listName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByListName($listName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($listName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $listName)) {
                $listName = str_replace('*', '%', $listName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomListsPeer::LIST_NAME, $listName, $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(CustomListsPeer::ID_USER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(CustomListsPeer::ID_USER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListsPeer::ID_USER, $idUser, $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CustomListsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CustomListsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListsPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CustomListsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CustomListsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CustomListsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(CustomListsPeer::ID_USER, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomListsPeer::ID_USER, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
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
     * Filter the query by a related CustomListElement object
     *
     * @param   CustomListElement|PropelObjectCollection $customListElement  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CustomListsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCustomListElement($customListElement, $comparison = null)
    {
        if ($customListElement instanceof CustomListElement) {
            return $this
                ->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $customListElement->getCustomList(), $comparison);
        } elseif ($customListElement instanceof PropelObjectCollection) {
            return $this
                ->useCustomListElementQuery()
                ->filterByPrimaryKeys($customListElement->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomListElement() only accepts arguments of type CustomListElement or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomListElement relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function joinCustomListElement($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomListElement');

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
            $this->addJoinObject($join, 'CustomListElement');
        }

        return $this;
    }

    /**
     * Use the CustomListElement relation CustomListElement object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Org\CoreBundle\Propel\CustomListElementQuery A secondary query class using the current class as primary query
     */
    public function useCustomListElementQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomListElement($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomListElement', '\Org\CoreBundle\Propel\CustomListElementQuery');
    }

    /**
     * Filter the query by a related CyclicalEventHasList object
     *
     * @param   CyclicalEventHasList|PropelObjectCollection $cyclicalEventHasList  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CustomListsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCyclicalEventHasList($cyclicalEventHasList, $comparison = null)
    {
        if ($cyclicalEventHasList instanceof CyclicalEventHasList) {
            return $this
                ->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $cyclicalEventHasList->getIdList(), $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
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
     * Filter the query by a related EventHasList object
     *
     * @param   EventHasList|PropelObjectCollection $eventHasList  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CustomListsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventHasList($eventHasList, $comparison = null)
    {
        if ($eventHasList instanceof EventHasList) {
            return $this
                ->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $eventHasList->getIdList(), $comparison);
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
     * @return CustomListsQuery The current query, for fluid interface
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
     * Filter the query by a related CyclicalEvents object
     * using the cyclical_event_has_list table as cross reference
     *
     * @param   CyclicalEvents $cyclicalEvents the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CustomListsQuery The current query, for fluid interface
     */
    public function filterByCyclicalEvents($cyclicalEvents, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCyclicalEventHasListQuery()
            ->filterByCyclicalEvents($cyclicalEvents, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Events object
     * using the event_has_list table as cross reference
     *
     * @param   Events $events the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CustomListsQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useEventHasListQuery()
            ->filterByEvents($events, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   CustomLists $customLists Object to remove from the list of results
     *
     * @return CustomListsQuery The current query, for fluid interface
     */
    public function prune($customLists = null)
    {
        if ($customLists) {
            $this->addUsingAlias(CustomListsPeer::ID_CUSTOM_LIST, $customLists->getIdCustomList(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomListsPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomListsPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomListsPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomListsPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomListsPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CustomListsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomListsPeer::CREATED_AT);
    }
}
