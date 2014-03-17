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

use DateTime;
use Doctrine\DBAL\Connection;
use hu\szjani\presentation\api\IssueDto;
use hu\szjani\presentation\api\IssueFinder;
use predaddy\presentation\Page;
use predaddy\presentation\Pageable;
use predaddy\presentation\PageImpl;

class DbalIssueFinder extends AbstractPageableFinderDbalImpl implements IssueFinder
{
    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        parent::__construct($connection);
    }

    /**
     * @param Pageable $pageable
     * @return Page
     */
    public function getPage(Pageable $pageable)
    {
        $queryBuilder = $this->createQueryBuilder($pageable);
        $content = $this->createDtoList($queryBuilder);
        $total = $this->calculateTotalNumber($queryBuilder);
        return new PageImpl($content, $pageable, $total);
    }

    protected function createDto(array $row)
    {
        return new IssueDto(
            $row['issue_id'],
            new DateTime($row['last_updated']),
            $row['name'],
            $row['state'],
            $row['user_name'],
            $row['version']
        );
    }

    protected function getIdName()
    {
        return 'issue_id';
    }

    protected function getTableName()
    {
        return 'issue';
    }
}
