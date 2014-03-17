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

use Closure;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use predaddy\presentation\Pageable;

abstract class AbstractIdentifiableFinderDbalImpl extends AbstractFinderDbalImpl
{
    abstract protected function getIdName();

    abstract protected function getTableName();

    public function findOneById($id)
    {
        $idName = $this->getIdName();
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->where("l.{$idName} = :id")
            ->setParameter('id', $id);
        $list = $this->createDtoList($queryBuilder);
        if (count($list) != 1) {
            throw new InvalidArgumentException("Invalid ID: $id");
        }
        return $list[0];
    }

    protected function createDtoList(QueryBuilder $queryBuilder, Closure $dtoBuilder = null)
    {
        $stmt = $queryBuilder->execute();
        $content = array();
        while ($row = $stmt->fetch()) {
            $dto = $dtoBuilder !== null
                ? $dtoBuilder($row)
                : $this->createDto($row);
            $content[] = $dto;
        }
        return $content;
    }

    /**
     * @param Pageable $pageable
     * @return QueryBuilder
     */
    protected function createQueryBuilder(Pageable $pageable = null)
    {
        $tableName = $this->getTableName();
        $queryBuilder = parent::createQueryBuilder($pageable);
        $queryBuilder
            ->select('*')
            ->from("`{$tableName}`", 'l');
        return $queryBuilder;
    }
}
