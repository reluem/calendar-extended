<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Contao\Database;

use Contao\CoreBundle\Tests\Fixtures\Database\DoctrineArrayStatement;
use Contao\Database\Result;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Database Result class.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 *
 * @group contao3
 */
class ResultTest extends TestCase
{
    public function testEmptyResult()
    {
        $result = new Result(new DoctrineArrayStatement([]), 'SELECT from test');

        $this->assertFalse($result->isModified);
        $this->assertSame(0, $result->numFields);
        $this->assertSame(0, $result->numRows);
        $this->assertSame('SELECT from test', $result->query);
        $this->assertSame(0, $result->count());
        $this->assertSame([], $result->fetchAllAssoc());
        $this->assertFalse($result->fetchAssoc());
        $this->assertSame([], $result->fetchEach('test'));
        $this->assertFalse($result->fetchRow());
    }

    public function testSingleRow()
    {
        $data = [
            ['field' => 'value1'],
        ];

        $result = new Result(new DoctrineArrayStatement($data), 'SELECT from test');

        $this->assertFalse($result->isModified);
        $this->assertSame(1, $result->numFields);
        $this->assertSame(1, $result->numRows);
        $this->assertSame('SELECT from test', $result->query);
        $this->assertSame(1, $result->count());
        $this->assertSame($data, $result->fetchAllAssoc());
        $this->assertSame($data[0], $result->reset()->fetchAssoc());
        $this->assertFalse($result->fetchAssoc());
        $this->assertSame(['value1'], $result->fetchEach('field'));
        $this->assertSame(array_values($data[0]), $result->reset()->fetchRow());
        $this->assertFalse($result->fetchRow());

        $this->assertSame($data[0], $result->reset()->row());
        $this->assertSame(array_values($data[0]), $result->last()->row(true));

        $this->assertSame('value1', $result->first()->field);
        $this->assertFalse($result->prev());
        $this->assertSame('value1', $result->last()->field);
        $this->assertFalse($result->next());

        $result->field = 'new value';
        $this->assertSame('new value', $result->field);
        $this->assertSame(['field' => 'new value'], $result->row());
        $this->assertSame(['new value'], $result->row(true));
    }

    public function testMultipleRows()
    {
        $data = [
            ['field' => 'value1'],
            ['field' => 'value2'],
        ];

        $result = new Result(new DoctrineArrayStatement($data), 'SELECT from test');

        $this->assertFalse($result->isModified);
        $this->assertSame(1, $result->numFields);
        $this->assertSame(2, $result->numRows);
        $this->assertSame('SELECT from test', $result->query);
        $this->assertSame(2, $result->count());
        $this->assertSame($data, $result->fetchAllAssoc());
        $this->assertSame($data[0], $result->reset()->fetchAssoc());
        $this->assertSame($data[1], $result->fetchAssoc());
        $this->assertSame(['value1', 'value2'], $result->fetchEach('field'));
        $this->assertSame(array_values($data[0]), $result->reset()->fetchRow());
        $this->assertSame(array_values($data[1]), $result->fetchRow());
        $this->assertFalse($result->fetchRow());

        $this->assertSame($data[0], $result->reset()->row());
        $this->assertSame(array_values($data[1]), $result->last()->row(true));

        $this->assertSame('value1', $result->first()->field);
        $this->assertFalse($result->prev());
        $this->assertSame('value2', $result->next()->field);
        $this->assertSame('value1', $result->prev()->field);
        $this->assertSame('value2', $result->last()->field);
        $this->assertFalse($result->next());

        $result->field = 'new value';
        $this->assertSame('new value', $result->field);
        $this->assertSame(['field' => 'new value'], $result->row());
        $this->assertSame(['new value'], $result->row(true));
    }

    public function testFetchRowAndAssoc()
    {
        $data = [
            ['field' => 'value1'],
            ['field' => 'value2'],
        ];

        $result = new Result(new DoctrineArrayStatement($data), 'SELECT from test');

        $this->assertSame(['field' => 'value1'], $result->fetchAssoc());
        $this->assertSame(['field' => 'value1'], $result->row());
        $this->assertSame('value1', $result->field);
        $this->assertNull($result->{'0'});

        $this->assertSame(['value2'], $result->fetchRow());
        $this->assertSame(['field' => 'value2'], $result->row());
        $this->assertSame('value2', $result->field);
        $this->assertNull($result->{'0'});
    }
}
