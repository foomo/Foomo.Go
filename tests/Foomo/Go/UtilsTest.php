<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo\Go;
use Foomo\Services\Mock\Nest\Bird;
use Foomo\Services\Reflection\ServiceObjectType;
use Schild\Utils\Log\Event;
use Schild\Utils\Log\Vo\User;
use Schild\Utils\Log\Vo\Item;
use Schild\Utils\Log\Vo\Product;
/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class UtilsTest extends \PHPUnit_Framework_TestCase
{
	public function testCrap()
	{
		Utils::writeStructsForValueObjects(
			[
				'Schild\\Utils\\Log\\Event',
				'Schild\\Utils\\Log\\Vo\\Item',
				'Schild\\Utils\\Log\\Vo\\Product',
				'Schild\\Utils\\Log\\Vo\\User',
			],
			'git.bestbytes.net/Project-Data-Flow/log',
			Module::getVarDir()
		);
		Utils::writeStructsForValueObjects(
			[
				'Schild\\Vo\\Persistence\\Product'
			],
			'git.bestbytes.net/Project-Data-Flow/shop',
			Module::getVarDir()
		);

	}
}