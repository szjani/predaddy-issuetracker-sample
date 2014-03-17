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

use Doctrine\DBAL\Query\QueryBuilder;
use predaddy\presentation\Pageable;
use predaddy\presentation\PageImpl;

abstract class AbstractPageableFinderDbalImpl extends AbstractIdentifiableFinderDbalImpl
{
    public function findAll(Pageable $pageable)
    {
        $queryBuilder = $this->createQueryBuilder($pageable);
        $content = $this->createDtoList($queryBuilder);
        $total = $this->calculateTotalNumber($queryBuilder);
        return new PageImpl($content, $pageable, $total);
    }

    protected function calculateTotalNumber(QueryBuilder $queryBuilder)
    {
        $firstResult = $queryBuilder->getFirstResult();
        $maxResults = $queryBuilder->getMaxResults();
        $queryBuilder
            ->setFirstResult(null)
            ->setMaxResults(null);
        $idName = $this->getIdName();
        $counter = $queryBuilder->select("COUNT($idName) as counter")->execute()->fetch();
        $queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults);
        return $counter['counter'];
    }
}
