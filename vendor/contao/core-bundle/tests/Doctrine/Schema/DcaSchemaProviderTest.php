<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Doctrine\Schema;

use Contao\CoreBundle\Doctrine\Schema\DcaSchemaProvider;
use Contao\CoreBundle\Tests\DoctrineTestCase;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Tests the DcaSchemaProvider class.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class DcaSchemaProviderTest extends DoctrineTestCase
{
    /**
     * Tests that the schema is empty.
     */
    public function testHasAnEmptySchema()
    {
        $this->assertCount(0, $this->getProvider()->createSchema()->getTableNames());
    }

    /**
     * Tests creating a schema.
     *
     * @param array $dca
     * @param array $sql
     *
     * @dataProvider createSchemaProvider
     */
    public function testCreatesASchema(array $dca = [], array $sql = [])
    {
        $schema = $this->getProvider($dca, $sql)->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_member'));

        $table = $schema->getTable('tl_member');

        $this->assertTrue($table->hasColumn('id'));
        $this->assertSame('integer', $table->getColumn('id')->getType()->getName());
        $this->assertTrue($table->getColumn('id')->getNotnull());
        $this->assertFalse($table->getColumn('id')->getFixed());

        if (null !== ($default = $table->getColumn('id')->getDefault())) {
            $this->assertSame('0', $default);
        }

        $this->assertTrue($table->hasColumn('pid'));
        $this->assertSame('integer', $table->getColumn('pid')->getType()->getName());
        $this->assertFalse($table->getColumn('pid')->getNotnull());
        $this->assertFalse($table->getColumn('pid')->getFixed());

        $this->assertTrue($table->hasColumn('title'));
        $this->assertSame('string', $table->getColumn('title')->getType()->getName());
        $this->assertTrue($table->getColumn('title')->getNotnull());
        $this->assertFalse($table->getColumn('title')->getFixed());
        $this->assertSame(128, $table->getColumn('title')->getLength());
        $this->assertSame('utf8_bin', $table->getColumn('title')->getPlatformOption('collation'));

        if (null !== ($default = $table->getColumn('title')->getDefault())) {
            $this->assertSame('', $default);
        }

        $this->assertTrue($table->hasColumn('uppercase'));
        $this->assertSame('string', $table->getColumn('uppercase')->getType()->getName());
        $this->assertTrue($table->getColumn('uppercase')->getNotnull());
        $this->assertFalse($table->getColumn('uppercase')->getFixed());
        $this->assertSame(64, $table->getColumn('uppercase')->getLength());
        $this->assertSame('Foobar', $table->getColumn('uppercase')->getDefault());

        $this->assertTrue($table->hasColumn('teaser'));
        $this->assertSame('text', $table->getColumn('teaser')->getType()->getName());
        $this->assertFalse($table->getColumn('teaser')->getNotnull());
        $this->assertFalse($table->getColumn('teaser')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_TINYTEXT, $table->getColumn('teaser')->getLength());

        $this->assertTrue($table->hasColumn('description'));
        $this->assertSame('text', $table->getColumn('description')->getType()->getName());
        $this->assertFalse($table->getColumn('description')->getNotnull());
        $this->assertFalse($table->getColumn('description')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_TEXT, $table->getColumn('description')->getLength());

        $this->assertTrue($table->hasColumn('content'));
        $this->assertSame('text', $table->getColumn('content')->getType()->getName());
        $this->assertFalse($table->getColumn('content')->getNotnull());
        $this->assertFalse($table->getColumn('content')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT, $table->getColumn('content')->getLength());

        $this->assertTrue($table->hasColumn('price'));
        $this->assertSame('decimal', $table->getColumn('price')->getType()->getName());
        $this->assertTrue($table->getColumn('price')->getNotnull());
        $this->assertFalse($table->getColumn('price')->getFixed());
        $this->assertSame(6, $table->getColumn('price')->getPrecision());
        $this->assertSame(2, $table->getColumn('price')->getScale());
        $this->assertSame('0.00', $table->getColumn('price')->getDefault());

        $this->assertTrue($table->hasColumn('thumb'));
        $this->assertSame('blob', $table->getColumn('thumb')->getType()->getName());
        $this->assertFalse($table->getColumn('thumb')->getNotnull());
        $this->assertFalse($table->getColumn('thumb')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_TINYBLOB, $table->getColumn('thumb')->getLength());

        $this->assertTrue($table->hasColumn('image'));
        $this->assertSame('blob', $table->getColumn('image')->getType()->getName());
        $this->assertFalse($table->getColumn('image')->getNotnull());
        $this->assertFalse($table->getColumn('image')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_BLOB, $table->getColumn('image')->getLength());

        $this->assertTrue($table->hasColumn('attachment'));
        $this->assertSame('blob', $table->getColumn('attachment')->getType()->getName());
        $this->assertFalse($table->getColumn('attachment')->getNotnull());
        $this->assertFalse($table->getColumn('attachment')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_MEDIUMBLOB, $table->getColumn('attachment')->getLength());

        $this->assertTrue($table->hasColumn('published'));
        $this->assertSame('string', $table->getColumn('published')->getType()->getName());
        $this->assertTrue($table->getColumn('published')->getNotnull());
        $this->assertTrue($table->getColumn('published')->getFixed());

        if (null !== ($default = $table->getColumn('published')->getDefault())) {
            $this->assertSame('', $default);
        }
    }

    /**
     * Provides the data for the schema test.
     *
     * @return array
     */
    public function createSchemaProvider()
    {
        return [
            // Test table fields SQL string from DCA file
            [
                [
                    'tl_member' => [
                        'TABLE_FIELDS' => [
                            'id' => "`id` int(10) NOT NULL default '0'",
                            'pid' => '`pid` int(10) NULL',
                            'title' => "`title` varchar(128) COLLATE utf8_bin NOT NULL default ''",
                            'uppercase' => "`uppercase` varchar(64) NOT NULL DEFAULT 'Foobar'",
                            'teaser' => '`teaser` tinytext NULL',
                            'description' => '`description` text NULL',
                            'content' => '`content` mediumtext NULL',
                            'price' => "`price` decimal(6,2) NOT NULL default '0.00'",
                            'thumb' => '`thumb` tinyblob NULL',
                            'image' => '`image` blob NULL',
                            'attachment' => '`attachment` mediumblob NULL',
                            'published' => "`published` char(1) NOT NULL default ''",
                        ],
                    ],
                ],
            ],

            // Test schema definition from DCA file
            [
                [
                    'tl_member' => [
                        'SCHEMA_FIELDS' => [
                            ['name' => 'id', 'type' => 'integer'],
                            ['name' => 'pid', 'type' => 'integer', 'notnull' => false],
                            ['name' => 'title', 'type' => 'string', 'length' => 128, 'platformOptions' => ['collation' => 'utf8_bin']],
                            ['name' => 'uppercase', 'type' => 'string', 'length' => 64, 'default' => 'Foobar'],
                            ['name' => 'teaser', 'type' => 'text', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_TINYTEXT],
                            ['name' => 'description', 'type' => 'text', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_TEXT],
                            ['name' => 'content', 'type' => 'text', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT],
                            ['name' => 'price', 'type' => 'decimal', 'precision' => 6, 'scale' => 2, 'default' => '0.00'],
                            ['name' => 'thumb', 'type' => 'blob', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_TINYBLOB],
                            ['name' => 'image', 'type' => 'blob', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_BLOB],
                            ['name' => 'attachment', 'type' => 'blob', 'notnull' => false, 'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMBLOB],
                            ['name' => 'published', 'type' => 'string', 'fixed' => true, 'length' => 1],
                        ],
                    ],
                ],
            ],

            // Test table fields from database.sql file
            [
                [],
                [
                    'tl_member' => [
                        'TABLE_FIELDS' => [
                            'id' => "`id` int(10) NOT NULL default '0'",
                            'pid' => '`pid` int(10) NULL',
                            'title' => "`title` varchar(128) COLLATE utf8_bin NOT NULL default ''",
                            'uppercase' => "`uppercase` varchar(64) NOT NULL DEFAULT 'Foobar'",
                            'teaser' => '`teaser` tinytext NULL',
                            'description' => '`description` text NULL',
                            'content' => '`content` mediumtext NULL',
                            'price' => "`price` decimal(6,2) NOT NULL default '0.00'",
                            'thumb' => '`thumb` tinyblob NULL',
                            'image' => '`image` blob NULL',
                            'attachment' => '`attachment` mediumblob NULL',
                            'published' => "`published` char(1) NOT NULL default ''",
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test the table options.
     */
    public function testReadsTheTableOptions()
    {
        $provider = $this->getProvider(['tl_member' => ['TABLE_OPTIONS' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8']]);
        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_member'));

        $this->assertSame('MyISAM', $schema->getTable('tl_member')->getOption('engine'));
        $this->assertSame('utf8', $schema->getTable('tl_member')->getOption('charset'));

        $provider = $this->getProvider([], ['tl_member' => ['TABLE_OPTIONS' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8']]);
        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_member'));

        $this->assertSame('MyISAM', $schema->getTable('tl_member')->getOption('engine'));
        $this->assertSame('utf8', $schema->getTable('tl_member')->getOption('charset'));

        $provider = $this->getProvider(['tl_member' => ['TABLE_OPTIONS' => 'ENGINE=InnoDB DEFAULT CHARSET=Latin1']]);
        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_member'));

        $this->assertSame('InnoDB', $schema->getTable('tl_member')->getOption('engine'));
        $this->assertSame('Latin1', $schema->getTable('tl_member')->getOption('charset'));
    }

    /**
     * Tests the table create definitions.
     */
    public function testCreatesTheTableDefinitions()
    {
        $provider = $this->getProvider(
            [
                'tl_member' => [
                    'TABLE_FIELDS' => [
                        'id' => "`id` int(10) NOT NULL default '0'",
                        'pid' => '`pid` int(10) NULL',
                        'username' => "`username` varchar(128) NOT NULL default ''",
                        'firstname' => "`firstname` varchar(128) NOT NULL default ''",
                        'lastname' => "`lastname` varchar(128) NOT NULL default ''",
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'PRIMARY' => 'PRIMARY KEY (`id`)',
                        'pid' => 'KEY `pid` (`pid`)',
                        'username' => 'UNIQUE KEY `username` (`username`)',
                        'name' => 'KEY `name` (`firstname`, `lastname`)',
                    ],
                ],
            ]
        );

        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_member'));

        $table = $schema->getTable('tl_member');

        $this->assertTrue($table->hasIndex('PRIMARY'));
        $this->assertTrue($table->getIndex('PRIMARY')->isPrimary());
        $this->assertSame(['id'], $table->getIndex('PRIMARY')->getColumns());

        $this->assertTrue($table->hasIndex('pid'));
        $this->assertFalse($table->getIndex('pid')->isUnique());
        $this->assertSame(['pid'], $table->getIndex('pid')->getColumns());

        $this->assertTrue($table->hasIndex('username'));
        $this->assertTrue($table->getIndex('username')->isUnique());
        $this->assertSame(['username'], $table->getIndex('username')->getColumns());

        $this->assertTrue($table->hasIndex('name'));
        $this->assertFalse($table->getIndex('name')->isUnique());
        $this->assertSame(['firstname', 'lastname'], $table->getIndex('name')->getColumns());
    }

    /**
     * Tests adding an index with a key length.
     */
    public function testHandlesIndexesWithKeyLength()
    {
        $provider = $this->getProvider(
            [
                'tl_files' => [
                    'TABLE_FIELDS' => [
                        'path' => "`path` varchar(1022) NOT NULL default ''",
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'path' => 'KEY `path` (`path`(333))',
                    ],
                ],
            ]
        );

        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_files'));

        $table = $schema->getTable('tl_files');

        $this->assertTrue($table->hasColumn('path'));
        $this->assertSame('string', $table->getColumn('path')->getType()->getName());
        $this->assertSame(1022, $table->getColumn('path')->getLength());

        $this->assertTrue($table->hasIndex('path'));
        $this->assertFalse($table->getIndex('path')->isUnique());
        $this->assertSame([333], $table->getIndex('path')->getOption('lengths'));

        if (method_exists(AbstractPlatform::class, 'supportsColumnLengthIndexes')) {
            $this->assertSame(['path'], $table->getIndex('path')->getColumns());
        } else {
            $this->assertSame(['path(333)'], $table->getIndex('path')->getColumns());
        }
    }

    /**
     * Tests adding an index over multiple columns.
     */
    public function testHandlesIndexesOverMultipleColumns()
    {
        $provider = $this->getProvider(
            [
                'tl_foo' => [
                    'TABLE_FIELDS' => [
                        'col1' => "`col1` varchar(255) NOT NULL default ''",
                        'col2' => "`col2` varchar(255) NOT NULL default ''",
                        'col3' => "`col3` varchar(255) NOT NULL default ''",
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'col123' => 'KEY `col123` (`col1`(100), `col2`, `col3`(99))',
                    ],
                ],
            ]
        );

        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_foo'));

        $table = $schema->getTable('tl_foo');

        for ($i = 1; $i <= 3; ++$i) {
            $this->assertTrue($table->hasColumn('col'.$i));
            $this->assertSame('string', $table->getColumn('col'.$i)->getType()->getName());
            $this->assertSame(255, $table->getColumn('col'.$i)->getLength());
        }

        $this->assertTrue($table->hasIndex('col123'));
        $this->assertFalse($table->getIndex('col123')->isUnique());
        $this->assertSame([100, null, 99], $table->getIndex('col123')->getOption('lengths'));

        if (method_exists(AbstractPlatform::class, 'supportsColumnLengthIndexes')) {
            $this->assertSame(['col1', 'col2', 'col3'], $table->getIndex('col123')->getColumns());
        } else {
            $this->assertSame(['col1(100)', 'col2', 'col3(99)'], $table->getIndex('col123')->getColumns());
        }
    }

    /**
     * Tests adding a fulltext index.
     */
    public function testHandlesFulltextIndexes()
    {
        $provider = $this->getProvider(
            [
                'tl_search' => [
                    'TABLE_FIELDS' => [
                        'text' => '`text` mediumtext NULL',
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'text' => 'FULLTEXT KEY `text` (`text`)',
                    ],
                ],
            ]
        );

        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertTrue($schema->hasTable('tl_search'));

        $table = $schema->getTable('tl_search');

        $this->assertTrue($table->hasColumn('text'));
        $this->assertSame('text', $table->getColumn('text')->getType()->getName());
        $this->assertFalse($table->getColumn('text')->getNotnull());
        $this->assertFalse($table->getColumn('text')->getFixed());
        $this->assertSame(MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT, $table->getColumn('text')->getLength());

        $this->assertTrue($table->hasIndex('text'));
        $this->assertFalse($table->getIndex('text')->isUnique());
        $this->assertSame(['fulltext'], $table->getIndex('text')->getFlags());
    }

    /**
     * Tests adding a schema filter.
     */
    public function testAppliesTheSchemaFilterToTheSqlDefinitions()
    {
        $provider = $this->getProvider(['member' => [], 'tl_member' => []], [], '/^tl_/');
        $schema = $provider->createSchema();

        $this->assertCount(1, $schema->getTableNames());
        $this->assertFalse($schema->hasTable('member'));
        $this->assertTrue($schema->hasTable('tl_member'));
    }

    /**
     * Tests parsing an invalid primary key.
     */
    public function testFailsIfThePrimaryKeyIsInvalid()
    {
        $provider = $this->getProvider(
            [
                'tl_member' => [
                    'TABLE_FIELDS' => [
                        'id' => "`id` int(10) NOT NULL default '0'",
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'PRIMARY' => 'PRIMARY KEY (id)',
                    ],
                ],
            ]
        );

        $this->expectException('RuntimeException');

        $provider->createSchema();
    }

    /**
     * Tests parsing an invalid key.
     */
    public function testFailsIfAKeyIsInvalid()
    {
        $provider = $this->getProvider(
            [
                'tl_files' => [
                    'TABLE_FIELDS' => [
                        'path' => "`path` varchar(1022) NOT NULL default ''",
                    ],
                    'TABLE_CREATE_DEFINITIONS' => [
                        'path' => 'KEY path (path)',
                    ],
                ],
            ]
        );

        $this->expectException('RuntimeException');

        $provider->createSchema();
    }

    /**
     * Tests creating the schema from ORM.
     */
    public function testCreatesSchemaFromOrm()
    {
        $metadata = new ClassMetadata('tl_member');
        $metadata->setTableName('tl_member');

        $provider = new DcaSchemaProvider(
            $this->mockContaoFrameworkWithInstaller(),
            $this->mockDoctrineRegistryWithOrm([$metadata])
        );

        $schema = $provider->createSchema();

        $this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $schema);
        $this->assertCount(1, $schema->getTables());
        $this->assertTrue($schema->hasTable('tl_member'));
    }

    /**
     * Tests adding a schema filter.
     */
    public function testAppliesTheSchemaFilterToTheOrmEntities()
    {
        $class1 = new ClassMetadata('tl_member');
        $class1->setTableName('tl_member');

        $class2 = new ClassMetadata('member');
        $class2->setTableName('member');

        $provider = new DcaSchemaProvider(
            $this->mockContaoFrameworkWithInstaller(),
            $this->mockDoctrineRegistryWithOrm([$class1, $class2], '/^tl_/')
        );

        $schema = $provider->createSchema();

        $this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $schema);
        $this->assertCount(1, $schema->getTables());
        $this->assertTrue($schema->hasTable('tl_member'));
        $this->assertFalse($schema->hasTable('member'));
    }

    /**
     * Tests adding a schema from ORM if there is no metadata.
     */
    public function testDoesNotCreateTheSchemaFromOrmIfThereIsNoMetadata()
    {
        $provider = new DcaSchemaProvider(
            $this->mockContaoFrameworkWithInstaller(),
            $this->mockDoctrineRegistryWithOrm()
        );

        $schema = $provider->createSchema();

        $this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $schema);
    }

    /**
     * Returns a DCA schema provider.
     *
     * @param array $dca
     * @param array $file
     * @param null  $filter
     *
     * @return DcaSchemaProvider
     */
    protected function getProvider(array $dca = [], array $file = [], $filter = null)
    {
        return new DcaSchemaProvider(
            $this->mockContaoFrameworkWithInstaller($dca, $file),
            $this->mockDoctrineRegistry($filter)
        );
    }
}
