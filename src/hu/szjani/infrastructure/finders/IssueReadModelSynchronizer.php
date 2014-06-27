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
use hu\szjani\domain\issue\IssueCreated;
use hu\szjani\domain\issue\Reassigned;
use PDO;
use predaddy\messagehandling\annotation\Subscribe;

class IssueReadModelSynchronizer
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Subscribe
     * @param IssueCreated $event
     */
    public function issueCreated(IssueCreated $event)
    {
        $this->connection->insert(
            'issue',
            [
                'issue_id' => $event->aggregateId()->value(),
                'name' => $event->getName(),
                'user_name' => $event->getAssignedUserName(),
                'state' => $event->getState(),
                'last_updated' => $event->created(),
                'state_hash' => $event->stateHash()
            ],
            [
                PDO::PARAM_INT,
                PDO::PARAM_STR,
                PDO::PARAM_STR,
                PDO::PARAM_STR,
                "datetime",
                PDO::PARAM_INT,
            ]
        );
    }

    /**
     * @Subscribe
     * @param Reassigned $event
     */
    public function issueReassigned(Reassigned $event)
    {
        $this->connection->update(
            'issue',
            [
                'user_name' => $event->getNewUserName(),
                'last_updated' => $event->created(),
                'state_hash' => $event->stateHash()
            ],
            [
                'issue_id' => $event->aggregateId()->value()
            ],
            [
                PDO::PARAM_STR,
                "datetime",
                PDO::PARAM_INT
            ]
        );
    }
}
