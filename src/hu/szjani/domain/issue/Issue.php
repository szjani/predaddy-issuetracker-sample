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

use precore\util\UUID;
use predaddy\domain\AbstractEventSourcedAggregateRoot;
use predaddy\domain\AggregateId;
use predaddy\domain\UUIDAggregateId;
use predaddy\messagehandling\annotation\Subscribe;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;


/**
 * Class Issue
 *
 * @package hu\szjani\domain\issue
 *
 * @author Szurovecz János <szjani@szjani.hu>
 * @ExclusionPolicy("none")
 */
class Issue extends AbstractEventSourcedAggregateRoot
{
    /**
     * @Type("predaddy\domain\DefaultAggregateId")
     * @var AggregateId
     */
    private $issueId;

    /**
     * @Type("string")
     * @var string
     */
    private $name;

    /**
     * @Type("string")
     * @var string
     */
    private $assignedUserName;

    /**
     * @Type("hu\szjani\domain\issue\State")
     * @var State
     */
    private $state;

    /**
     * @Subscribe
     * @param CreateIssue $command
     */
    public function __construct(CreateIssue $command)
    {
        \Assert\lazy()
            ->that($command->getName(), 'name')->string()->notEmpty()
            ->that($command->getAssignedUserName(), 'user name')->string()->notEmpty()
            ->verifyNow();

        $this->apply(
            new IssueCreated(
                new UUIDAggregateId(UUID::randomUUID()),
                $command->getName(),
                $command->getAssignedUserName(),
                State::$ASSIGNED->name(),
                $command->getVersion()
            )
        );
    }

    /**
     * @return AggregateId
     */
    public function getId()
    {
        return $this->issueId;
    }

    /**
     * @Subscribe
     * @param Reassign $command
     */
    public function reassign(Reassign $command)
    {
        \Assert\lazy()
            ->that($command->getNewUserName(), 'user name')->string()->notEmpty()
            ->that($this->state->equals(State::$CLOSED), 'current state')->false()
            ->verifyNow();
        $this->apply(new Reassigned($this->getId(), $command->getNewUserName(), $command->getVersion()));
    }

    /**
     * @Subscribe
     * @param IssueCreated $event
     */
    protected function created(IssueCreated $event)
    {
        $this->issueId = $event->getAggregateId();
        $this->name = $event->getName();
        $this->assignedUserName = $event->getAssignedUserName();
        $this->state = State::valueOf($event->getState());
    }

    /**
     * @Subscribe
     * @param Reassigned $event
     */
    protected function reassigned(Reassigned $event)
    {
        $this->assignedUserName = $event->getNewUserName();
    }
}
