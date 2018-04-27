<?php
/**
 * VuFind Configuration Manager Test Class
 *
 * PHP version 7
 *
 * Copyright (C) 2018 Leipzig University Library <info@ub.uni-leipzig.de>
 *
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  vufind-org/vufindconfig
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */

namespace VuFind\Config;

use Psr\Container\ContainerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * VuFind Configuration Manager Test Class
 *
 * @category VuFind
 * @package  vufind-org/vufindconfig
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
class ManagerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testWithBasicProvider()
    {
        $manager = $this->createManager('basic');

        $this->assertArraySubset(
            [
                'u' => 'w',
                'y' => 'z'
            ], $manager->getValue('nested/yaml')
        );

        $this->assertArraySubset(
            [
                'a' => 2,
                'b' => [
                    'c' => 1,
                    'd' => 0
                ]
            ], $manager->getValue('ini/S')
        );

        $this->assertArraySubset(
            [
                'key' => 43,
                '%weired;Key$\\' => true
            ], $manager->getValue('yaml')
        );

        $this->assertArrayHasKey(
            '@parent_yaml',
            $manager->getValue('yaml')
        );

        $this->assertArraySubset(
            [
                'ini' => [
                    'T' => [
                        'x' => 'z',
                        'a' => 'b',
                        'c' => 'd'
                    ],
                ],
                'json' => [
                    'u' => 'v',
                    'x' => 'y'
                ],
                'yaml' => [
                    'u' => 'w',
                    'y' => 'z'
                ]
            ], $manager->getValue('nested')
        );
    }

    /**
     * @throws \Exception
     */
    public function testWithClassicProvider()
    {
        $manager = $this->createManager('classic');

        $this->assertEquals(
            'w', $manager->getValue('nested/yaml/u')
        );

        $this->assertArrayNotHasKey(
            'y',
            $manager->getValue('nested/yaml')
        );

        $this->assertArraySubset(
            [
                'key' => 43,
                '%weired;Key$\\' => true
            ], $manager->getValue('yaml')
        );

        $this->assertArrayNotHasKey(
            '@parent_yaml', $manager->getValue('yaml')
        );

        $this->assertArraySubset(
            [
                'a' => 2,
                'b.c' => 1,
                'b.d' => 0
            ], $manager->getValue('ini/S')
        );

        $this->assertArraySubset(
            [
                'ini' => [
                    'T' => [
                        'x' => 'z',
                        'a' => 'b'
                    ],
                ],
                'json' => [
                    'x' => 'y'
                ],
                'yaml' => [
                    'u' => 'w'
                ]
            ], $manager->getValue('nested')
        );

        $this->assertArrayNotHasKey(
            'c', $manager->getValue('nested/ini/T')
        );
    }

    /**
     * Creates a manager.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    protected function createManager(string $key): Manager
    {
        /**
         * @var ContainerInterface|MockObject $container
         */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturnMap(
            [
                ['config', (new Module)->getConfig()]
            ]
        );

        $factory = new ManagerFactory;
        $manager = $factory(
            $container, Manager::class, [
                'cacheDir' => __DIR__ . "/cache/$key",
                'configPath' => __DIR__ . "/fixtures/$key.config.php",
            ]
        );

        $manager->reset();
        return $manager;
    }
}
