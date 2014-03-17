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

use PHPUnit_Framework_TestCase;

class IssueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldFailWithEmptyName()
    {
        $command = new CreateIssue("", "John");
        new Issue($command);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldFailWIthEmptyUserName()
    {
        $command = new CreateIssue("Issue 01", "");
        new Issue($command);
    }

    /**
     * @test
     */
    public function createIssue()
    {
        $name = "Issue 01";
        $assignedUserName = "John";
        $command = new CreateIssue($name, $assignedUserName);
        $issue = new Issue($command);
        $events = $issue->getAndClearRaisedEvents();
        self::assertEquals(1, count($events));
        $event = $events[0];
        self::assertInstanceOf(IssueCreated::className(), $event);
        /* @var $event IssueCreated */
        self::assertEquals($name, $event->getName());
        self::assertEquals($assignedUserName, $event->getAssignedUserName());
        self::assertEquals(1, $event->getVersion());
        self::assertEquals(State::$ASSIGNED->name(), $event->getState());
    }
}
