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

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class RosettaTest extends \PHPUnit_Framework_TestCase
{
	public static function testGetRecursionsInType()
	{
		echo implode(', ', Rosetta::getRecursionsInType(new ServiceObjectType("Foomo\\Services\\Mock\\Nest\\Bird")));
	}
	public static function testPhpVoToGoStructSource()
	{
		$known = Rosetta::getRecursionsInType(new ServiceObjectType("Foomo\\Services\\Mock\\Nest\\Bird"));
		foreach($known as $className) {
			echo Rosetta::phpVoToGoStructSource($className, $known);
		}
	}
}