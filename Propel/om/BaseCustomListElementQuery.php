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
use Org\CoreBundle\Propel\CustomListElement;
use Org\CoreBundle\Propel\CustomListElementPeer;
use Org\CoreBundle\Propel\CustomListElementQuery;
use Org\CoreBundle\Propel\CustomLists;

/**
 * @method CustomListElementQuery orderByIdElement($order = Criteria::ASC) Order by the id_element column
 * @method CustomListElementQuery orderByCustomList($order = Criteria::ASC) Order by the custom_list column
 * @method CustomListElementQuery orderByElementName($order = Criteria::ASC) Order by the element_name column
 * @method CustomListElementQuery orderByElementDescription($order = Criteria::ASC) Order by the element_description column
 * @method CustomListElementQuery orderByElementOrder($order = Criteria::ASC) Order by the element_order column
 * @method CustomListElementQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CustomListElementQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CustomListElementQuery groupByIdElement() Group by the id_element column
 * @method CustomListElementQuery groupByCustomList() Group by the custom_list column
 * @method CustomListElementQuery groupByElementName() Group by the element_name column
 * @method CustomListElementQuery groupByElementDescription() Group by the element_description column
 * @method CustomListElementQuery groupByElementOrder() Group by the element_order column
 * @method CustomListElementQuery groupByCreatedAt() Group by the created_at column
 * @method CustomListElementQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CustomListElementQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CustomListElementQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CustomListElementQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CustomListElementQuery leftJoinCustomLists($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomLists relation
 * @method CustomListElementQuery rightJoinCustomLists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomLists relation
 * @method CustomListElementQuery innerJoinCustomLists($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomLists relation
 *
 * @method CustomListElement findOne(PropelPDO $con = null) Return the first CustomListElement matching the query
 * @method CustomListElement findOneOrCreate(PropelPDO $con = null) Return the first CustomListElement matching the query, or a new CustomListElement object populated from the query conditions when no match is found
 *
 * @method CustomListElement findOneByCustomList(int $custom_list) Return the first CustomListElement filtered by the custom_list column
 * @method CustomListElement findOneByElementName(string $element_name) Return the first CustomListElement filtered by the element_name column
 * @method CustomListElement findOneByElementDescription(string $element_description) Return the first CustomListElement filtered by the element_description column
 * @method CustomListElement findOneByElementOrder(int $element_order) Return the first CustomListElement filtered by the element_order column
 * @method CustomListElement findOneByCreatedAt(string $created_at) Return the first CustomListElement filtered by the created_at column
 * @method CustomListElement findOneByUpdatedAt(string $updated_at) Return the first CustomListElement filtered by the updated_at column
 *
 * @method array findByIdElement(int $id_element) Return CustomListElement objects filtered by the id_element column
 * @method array findByCustomList(int $custom_list) Return CustomListElement objects filtered by the custom_list column
 * @method array findByElementName(string $element_name) Return CustomListElement objects filtered by the element_name column
 * @method array findByElementDescription(string $element_description) Return CustomListElement objects filtered by the element_description column
 * @method array findByElementOrder(int $element_order) Return CustomListElement objects filtered by the element_order column
 * @method array findByCreatedAt(string $created_at) Return CustomListElement objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CustomListElement objects filtered by the updated_at column
 */
abstract class BaseCustomListElementQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCustomListElementQuery object.
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
            $modelName = 'Org\\CoreBundle\\Propel\\CustomListElement';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CustomListElementQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CustomListElementQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CustomListElementQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CustomListElementQuery) {
            return $criteria;
        }
        $query = new CustomListElementQuery(null, null, $modelAlias);

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
     * @return   CustomListElement|CustomListElement[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomListElementPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CustomListElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CustomListElement A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByIdElement($key, $con = null)
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
     * @return                 CustomListElement A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id_element`, `custom_list`, `element_name`, `element_description`, `element_order`, `created_at`, `updated_at` FROM `custom_list_element` WHERE `id_element` = :p0';
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
            $obj = new CustomListElement();
            $obj->hydrate($row);
            CustomListElementPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CustomListElement|CustomListElement[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CustomListElement[]|mixed the list of results, formatted by the current formatter
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
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_element column
     *
     * Example usage:
     * <code>
     * $query->filterByIdElement(1234); // WHERE id_element = 1234
     * $query->filterByIdElement(array(12, 34)); // WHERE id_element IN (12, 34)
     * $query->filterByIdElement(array('min' => 12)); // WHERE id_element >= 12
     * $query->filterByIdElement(array('max' => 12)); // WHERE id_element <= 12
     * </code>
     *
     * @param     mixed $idElement The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByIdElement($idElement = null, $comparison = null)
    {
        if (is_array($idElement)) {
            $useMinMax = false;
            if (isset($idElement['min'])) {
                $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $idElement['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idElement['max'])) {
                $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $idElement['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $idElement, $comparison);
    }

    /**
     * Filter the query on the custom_list column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomList(1234); // WHERE custom_list = 1234
     * $query->filterByCustomList(array(12, 34)); // WHERE custom_list IN (12, 34)
     * $query->filterByCustomList(array('min' => 12)); // WHERE custom_list >= 12
     * $query->filterByCustomList(array('max' => 12)); // WHERE custom_list <= 12
     * </code>
     *
     * @see       filterByCustomLists()
     *
     * @param     mixed $customList The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByCustomList($customList = null, $comparison = null)
    {
        if (is_array($customList)) {
            $useMinMax = false;
            if (isset($customList['min'])) {
                $this->addUsingAlias(CustomListElementPeer::CUSTOM_LIST, $customList['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customList['max'])) {
                $this->addUsingAlias(CustomListElementPeer::CUSTOM_LIST, $customList['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::CUSTOM_LIST, $customList, $comparison);
    }

    /**
     * Filter the query on the element_name column
     *
     * Example usage:
     * <code>
     * $query->filterByElementName('fooValue');   // WHERE element_name = 'fooValue'
     * $query->filterByElementName('%fooValue%'); // WHERE element_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $elementName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByElementName($elementName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($elementName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $elementName)) {
                $elementName = str_replace('*', '%', $elementName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::ELEMENT_NAME, $elementName, $comparison);
    }

    /**
     * Filter the query on the element_description column
     *
     * Example usage:
     * <code>
     * $query->filterByElementDescription('fooValue');   // WHERE element_description = 'fooValue'
     * $query->filterByElementDescription('%fooValue%'); // WHERE element_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $elementDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByElementDescription($elementDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($elementDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $elementDescription)) {
                $elementDescription = str_replace('*', '%', $elementDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::ELEMENT_DESCRIPTION, $elementDescription, $comparison);
    }

    /**
     * Filter the query on the element_order column
     *
     * Example usage:
     * <code>
     * $query->filterByElementOrder(1234); // WHERE element_order = 1234
     * $query->filterByElementOrder(array(12, 34)); // WHERE element_order IN (12, 34)
     * $query->filterByElementOrder(array('min' => 12)); // WHERE element_order >= 12
     * $query->filterByElementOrder(array('max' => 12)); // WHERE element_order <= 12
     * </code>
     *
     * @param     mixed $elementOrder The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByElementOrder($elementOrder = null, $comparison = null)
    {
        if (is_array($elementOrder)) {
            $useMinMax = false;
            if (isset($elementOrder['min'])) {
                $this->addUsingAlias(CustomListElementPeer::ELEMENT_ORDER, $elementOrder['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($elementOrder['max'])) {
                $this->addUsingAlias(CustomListElementPeer::ELEMENT_ORDER, $elementOrder['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::ELEMENT_ORDER, $elementOrder, $comparison);
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
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CustomListElementPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CustomListElementPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CustomListElementPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CustomListElementPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomListElementPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related CustomLists object
     *
     * @param   CustomLists|PropelObjectCollection $customLists The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CustomListElementQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCustomLists($customLists, $comparison = null)
    {
        if ($customLists instanceof CustomLists) {
            return $this
                ->addUsingAlias(CustomListElementPeer::CUSTOM_LIST, $customLists->getIdCustomList(), $comparison);
        } elseif ($customLists instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomListElementPeer::CUSTOM_LIST, $customLists->toKeyValue('PrimaryKey', 'IdCustomList'), $comparison);
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
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function joinCustomLists($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useCustomListsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomLists($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomLists', '\Org\CoreBundle\Propel\CustomListsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CustomListElement $customListElement Object to remove from the list of results
     *
     * @return CustomListElementQuery The current query, for fluid interface
     */
    public function prune($customListElement = null)
    {
        if ($customListElement) {
            $this->addUsingAlias(CustomListElementPeer::ID_ELEMENT, $customListElement->getIdElement(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomListElementPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomListElementPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomListElementPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomListElementPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomListElementPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CustomListElementQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomListElementPeer::CREATED_AT);
    }
}
