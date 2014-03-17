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

namespace hu\szjani\presentation\web\controllers;

use hu\szjani\domain\issue\CreateIssue;
use hu\szjani\domain\issue\Reassign;
use hu\szjani\presentation\api\IssueFinder;
use predaddy\commandhandling\CommandBus;
use predaddy\presentation\PageRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class IssueController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var IssueFinder
     */
    private $issueFinder;

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(CommandBus $commandBus, IssueFinder $issueFinder, Twig_Environment $twig)
    {
        $this->commandBus = $commandBus;
        $this->issueFinder = $issueFinder;
        $this->twig = $twig;
    }

    public function index()
    {
        return $this->listAll(0);
    }

    public function create()
    {
        return $this->twig->render('create.twig');
    }

    public function createIssue(Request $request)
    {
        $issueName = $request->request->get('issue-name');
        $user = $request->request->get('user');
        $this->commandBus->post(new CreateIssue($issueName, $user));
        return new RedirectResponse('/');
    }

    public function reassign($aggregateId)
    {
        $issue = $this->issueFinder->findOneById($aggregateId);
        return $this->twig->render('reassign.twig', array('issue' => $issue));
    }

    public function reassignIssue($aggregateId, Request $request)
    {
        $user = $request->request->get('user');
        $version = $request->request->get('version');
        $this->commandBus->post(new Reassign($aggregateId, $user, $version));
        return new RedirectResponse('/');
    }

    public function listAll($pageNumber)
    {
        $pageRequest = new PageRequest($pageNumber, 10);
        $page = $this->issueFinder->getPage($pageRequest);
        return $this->twig->render('list.twig', array('page' => $page));
    }
}
