<?php
/**
 * finc Configuration Manager Factory
 *
 * Copyright (C) 2018 Leipzig University Library <info@ub.uni-leipzig.de>
 *
 * PHP version 7
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
 * along with this program; if not, write to the Free Software Foundation,
 * Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @category finc
 * @package  finc/config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://finc.info
 */
namespace finc\Config;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * finc Configuration Manager Factory
 *
 * @category finc
 * @package  finc/config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://finc.info
 */
class ManagerFactory implements FactoryInterface
{
    /**
     * Creates a {@see Manager} instance.
     *
     * @param ContainerInterface $container     The service container.
     * @param string             $requestedName The requested name.
     * @param array|null         $options       Options passed to
     *                                          {@see Manager::__construct()}
     *
     * @return Manager
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): Manager {
        Factory::init();

        $defaults = $container->get('config')['finc']['config'];
        $parameters = array_replace($defaults, $options ?? []);
        $configPath = $parameters['configPath'];

        if (!is_file($configPath)) {
            throw new \Exception("File $configPath does not exist!");
        }

        if (!is_dir($parameters['cacheDir'])) {
            mkdir($parameters['cacheDir'], 0755, true);
        }

        return new $requestedName(...array_values($parameters));
    }
}
