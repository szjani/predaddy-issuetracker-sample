<?php
/*
 * Copyright (c) 2012-2014 Szurovecz János
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace hu\szjani\infrastructure\finders;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use predaddy\presentation\Order;
use predaddy\presentation\Pageable;

/**
 * Abstract Finder class based on Doctrine DBAL.
 *
 * @author Szurovecz János <szjani@szjani.hu>
 */
abstract class AbstractFinderDbalImpl
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    abstract protected function createDto(array $row);

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param Pageable $pageable
     * @return QueryBuilder
     */
    protected function createQueryBuilder(Pageable $pageable = null)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        if ($pageable !== null) {
            $this->initQueryBuilder($queryBuilder, $pageable);
        }
        return $queryBuilder;
    }

    /**
     * Set limit, offset and ordering clauses.
     *
     * @param QueryBuilder $queryBuilder
     * @param Pageable $pageable
     */
    protected function initQueryBuilder(QueryBuilder $queryBuilder, Pageable $pageable)
    {
        $queryBuilder
            ->setFirstResult($pageable->getOffset())
            ->setMaxResults($pageable->getPageSize());
        if ($pageable->getSort() !== null) {
            /* @var $order Order */
            foreach ($pageable->getSort() as $order) {
                $queryBuilder->addOrderBy($order->getProperty(), $order->getDirection()->name());
            }
        }
    }
}
