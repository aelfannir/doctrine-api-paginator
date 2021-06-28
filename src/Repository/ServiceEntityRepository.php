<?php
declare(strict_types=1);

namespace AElfannir\DoctrineQueryPaginator\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as BaseServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ServiceEntityRepository
 * @package App\Repository
 */
class ServiceEntityRepository extends BaseServiceEntityRepository
{
    const COND_AND = 'AND';
    const COND_OR = 'OR';


    const STRING_EQ = 'STRING.EQ';
    const STRING_NEQ = 'STRING.NEQ';
    const STRING_IS_NULL = 'STRING.IS_NULL';
    const STRING_IS_NOT_NULL = 'STRING.IS_NOT_NULL';
    const STRING_IN = 'STRING.IN';
    const STRING_NOT_IN = 'STRING.NOT_IN';
    const STRING_CONTAINS = 'STRING.CONTAINS';
    const STRING_DOES_NOT_CONTAIN = 'STRING.DOES_NOT_CONTAIN';
    const STRING_STARTS_WITH = 'STRING.STARTS_WITH';
    const STRING_ENDS_WITH = 'STRING.ENDS_WITH';
    const STRING_DOES_NOT_START_WITH = 'STRING.DOES_NOT_START_WITH';
    const STRING_DOES_NOT_END_WITH = 'STRING.DOES_NOT_END_WITH';

    const NUMBER_EQ = 'NUMBER.EQ';
    const NUMBER_NEQ = 'NUMBER.NEQ';
    const NUMBER_IS_NULL = 'NUMBER.IS_NULL';
    const NUMBER_IS_NOT_NULL = 'NUMBER.IS_NOT_NULL';
    const NUMBER_GT = 'NUMBER.GT'; //
    const NUMBER_GTE = 'NUMBER.GTE';
    const NUMBER_LT = 'NUMBER.LT';
    const NUMBER_LTE = 'NUMBER.LTE';
    const NUMBER_IN = 'NUMBER.IN';
    const NUMBER_NOT_IN = 'NUMBER.NOT_IN';

    const DATETIME_EQ = 'DATETIME.EQ';
    const DATETIME_NEQ = 'DATETIME.NEQ';
    const DATETIME_IS_NULL = 'DATETIME.IS_NULL';
    const DATETIME_IS_NOT_NULL = 'DATETIME.IS_NOT_NULL';
    const DATETIME_GT = 'DATETIME.GT'; //
    const DATETIME_GTE = 'DATETIME.GTE';
    const DATETIME_LT = 'DATETIME.LT';
    const DATETIME_LTE = 'DATETIME.LTE';
    const DATETIME_IN = 'DATETIME.IN';
    const DATETIME_NOT_IN = 'DATETIME.NOT_IN';

    const BOOL_EQ = 'BOOL.EQ';
    const RELATION_EQ = 'RELATION.EQ';
    const RELATION_NEQ = 'RELATION.NEQ';
    const RELATION_IS_NULL = 'RELATION.IS_NULL';
    const RELATION_IS_NOT_NULL = 'RELATION.IS_NOT_NULL';

    /**
     * ServiceEntityRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->_entityName);
    }

    /**
     * @param Expr $expr
     * @param string $alias
     * @param array $filter
     * @param string $paramKey
     * @return string
     */
    public function getFilterExp($expr, $alias, $filter, $paramKey)
    {
        $operator = $filter['operator'];
        $property = $alias.'.'.$filter['property'];

        return match ($operator) {
            self::STRING_EQ,
            self::NUMBER_EQ,
            self::DATETIME_EQ,
            self::BOOL_EQ,
            self::RELATION_EQ
            => $expr->eq($property, ":$paramKey"),
            //
            self::STRING_NEQ,
            self::NUMBER_NEQ,
            self::DATETIME_NEQ,
            self::RELATION_NEQ
            => $expr->neq($property, ":$paramKey"),
            //
            self::STRING_IS_NULL,
            self::NUMBER_IS_NULL,
            self::DATETIME_IS_NULL,
            self::RELATION_IS_NULL
            => $expr->isNull($property),
            //
            self::STRING_IS_NOT_NULL,
            self::NUMBER_IS_NOT_NULL,
            self::DATETIME_IS_NOT_NULL,
            self::RELATION_IS_NOT_NULL
            => $expr->isNotNull($property),
            //
            self::STRING_IN,
            self::NUMBER_IN,
            self::DATETIME_IN
            => $expr->in($property, ":$paramKey"),
            //
            self::STRING_NOT_IN,
            self::NUMBER_NOT_IN,
            self::DATETIME_NOT_IN
            => $expr->notIn($property, ":$paramKey"),
            //
            self::STRING_CONTAINS,
            self::STRING_ENDS_WITH,
            self::STRING_STARTS_WITH
            => $expr->like($property, ":$paramKey"),
            //
            self::STRING_DOES_NOT_CONTAIN,
            self::STRING_DOES_NOT_START_WITH,
            self::STRING_DOES_NOT_END_WITH
            => $expr->notLike($property, ":$paramKey"),
            //
            self::NUMBER_GT,
            self::DATETIME_GT
            => $expr->gt($property, ":$paramKey"),
            //
            self::NUMBER_GTE,
            self::DATETIME_GTE
            => $expr->gte($property, ":$paramKey"),
            //
            self::NUMBER_LT,
            self::DATETIME_LT
            => $expr->lt($property, ":$paramKey"),
            //
            self::NUMBER_LTE, self::DATETIME_LTE
            => $expr->lte($property, ":$paramKey"),
        };
    }

    /**
     * @param QueryBuilder $QB
     * @param array $filter
     * @param string $paramKey
     */
    public function addParam(&$QB, $filter, $paramKey)
    {
        $operator = $filter['operator'];
        $value = $filter['value'];

        $paramValue = match ($operator) {
            self::STRING_CONTAINS, self::STRING_DOES_NOT_CONTAIN => "%$value%",
            self::STRING_STARTS_WITH, self::STRING_DOES_NOT_START_WITH => "$value%",
            self::STRING_ENDS_WITH, self::STRING_DOES_NOT_END_WITH => "%$value",
            default => $value
        };

        $hasParam = in_array(
            $operator,
            [
                self::STRING_IS_NULL, self::NUMBER_IS_NULL, self::DATETIME_IS_NULL, self::RELATION_IS_NULL,
                self::STRING_IS_NOT_NULL, self::NUMBER_IS_NOT_NULL, self::DATETIME_IS_NOT_NULL, self::RELATION_IS_NOT_NULL
            ]
        );

        if (! $hasParam) {
            $QB->setParameter($paramKey, $paramValue);
        }
    }

    /**
     * @param QueryBuilder $QB
     * @param $alias
     * @param $filter
     * @param string $cond
     * @param string $paramKey
     */
    public function addWhere(&$QB, $alias, $filter, $cond = self::COND_OR, $paramKey = '')
    {
        $expr = $QB->expr();

        $this->addParam($QB, $filter, $paramKey);
        $filterExpression = $this->getFilterExp($expr, $alias, $filter, $paramKey);

        if ($cond === self::COND_OR) {
            $QB->orWhere($filterExpression);
        } else {
            $QB->andWhere($filterExpression);
        }
    }

    public function filter(&$QB, $metaFilter, $alias)
    {
        if ($metaFilter) {
            $filterOperator = $metaFilter['operator'];
            $compoundFilters = $metaFilter['filters'];
            foreach ($compoundFilters as $i=>$filter) {
                $property = $filter['property'];
                if ($property) {
                    $paramKey = $alias.'_'.$property.'_'.$i;
                    $this->addWhere($QB, $alias, $filter, $filterOperator, $paramKey);
                } else {
                    $this->filter($QB, $metaFilter, $alias);
                }
            }
        }
    }

    public function search(&$QB, $search, $alias)
    {
        foreach ($this->getClassMetadata()->getFieldNames() as $fieldName){
            $paramKey = "{$alias}_$fieldName";
            $expression = $QB->expr()->like("$alias.$fieldName", ":$paramKey");
            $QB
                ->andWhere($expression)
                ->setParameter($paramKey, "%$search%")
            ;
        }
    }

    /**
     * @param QueryBuilder $QB
     * @param array $join
     * @param string $rootAlias
     */
    public function join(&$QB, $join, $rootAlias)
    {
        foreach ($join as $relation) {
            $table = $relation['table'];
            $relationAlias = $relation['alias'] ?? $table;
            $QB->leftJoin("$rootAlias.$table","$relationAlias");
            $QB->addSelect($relationAlias);
            //
            $nestedJoin = $relation['join'] ?? [];
            $this->join($QB, $nestedJoin, $relationAlias);
        }
    }

    /**
     * @param QueryBuilder $QB
     * @param array $sorts
     * @param string $alias
     */
    public function sort(&$QB, $sorts, $alias)
    {
        foreach ($sorts as $sort) {
            $sortBy = $sort['property'];
            $sortDirection = $sort['direction'];
            $QB->addOrderBy("$alias.$sortBy", $sortDirection);
        }
    }

    /**
     * @param array $meta
     * @param string $alias
     * @return QueryBuilder
     */
    public function createPaginateQueryBuilder($meta, $alias)
    {
        $QB = $this->createQueryBuilder($alias);

        // join
        $join = $meta['join'];
        $this->join($QB, $join, $alias);
        //sort
        $sorts = $meta['sorts'];
        $this->sort($QB, $sorts, $alias);
        //filters
        $filter = $meta['filter'];
        $this->filter($QB, $filter, $alias);
        //search
        $search = $meta['search'] ?? '';
        $this->search($QB, $search, $alias);

        return $QB;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function paginate($meta = []): array
    {
        $pages = $page = 1;

        if (empty($meta)) {
            $entities = $this->findAll();
            $total = $perPage = count($entities);
        } else {
            $alias = 't1';
            $QB = $this->createPaginateQueryBuilder($meta, $alias);

            //pagination
            $perPage = $meta['pagination']['perPage'];
            $page = $meta['pagination']['page'];
            $offset = max(0, ($page - 1) * $perPage);
            $QB
                ->setFirstResult($offset)
                ->setMaxResults($perPage);

            $paginator = new Paginator($QB);
            $total = $paginator->count();
            $pages = $total/$perPage;
            $pages = is_float($pages) ? (int)$pages+1 : $pages;

            $entities = [];
            foreach ($paginator as $entity) {
                $entities[] = $entity;
            }
        }

        return [
            'entities'=> $entities,
            'meta' => [
                'pagination' => [
                    'pages' => $pages,
                    'total' => $total,
                    'perPage' => $perPage,
                    'page' => $page,
                ]
            ]
        ];

    }

}