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

namespace hu\szjani\domain\issue;

use predaddy\domain\AbstractDomainEvent;
use predaddy\domain\AggregateId;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Class IssueCreated
 *
 * @package hu\szjani\domain\issue
 *
 * @author Szurovecz János <szjani@szjani.hu>
 * @ExclusionPolicy("none")
 */
class IssueCreated extends AbstractDomainEvent
{
    /**
     * @Type("string")
     * @var string
     */
    private $assignedUserName;

    /**
     * @Type("string")
     * @var string
     */
    private $state;

    /**
     * @Type("string")
     * @var string
     */
    private $name;

    public function __construct(AggregateId $aggregateId, $name, $assignedUserName, $state, $originatedVersion)
    {
        parent::__construct($aggregateId, $originatedVersion);
        $this->name = $name;
        $this->assignedUserName = $assignedUserName;
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAssignedUserName()
    {
        return $this->assignedUserName;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
