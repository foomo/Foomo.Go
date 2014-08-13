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
	public function testGetRecursionsInType()
	{
		$expected = [
			'Foomo\\Services\\Mock\\Nest\\Bird',
			'Foomo\\Services\\Mock\\Nest'
		];
		$actual = Rosetta::getRecursionsInType(new ServiceObjectType('Foomo\\Services\\Mock\\Nest\\Bird'));
		$this->assertTrue(sort($actual) && sort($expected));
		$this->assertEquals($expected, $actual);
	}
	public function testPhpVoToGoStructSource()
	{
		$known = Rosetta::getRecursionsInType(new ServiceObjectType('Foomo\\Services\\Mock\\Nest\\Bird'));
		$src = 'package test' . PHP_EOL;
		foreach($known as $className) {
			$this->assertNotEmpty($src .= Rosetta::phpVoToGoStructSource($className, $known));
		}
		$tmpFile = Module::getTempDir() . DIRECTORY_SEPARATOR .  'go-test.go';
		file_put_contents($tmpFile, $src);
		$cmd = \Foomo\CliCall::create('gofmt', ['-w=true', $tmpFile]);
		$cmd->execute();
		//unlink($tmpFile);
		$this->assertEquals(0, $cmd->exitStatus);
	}
	public function testConstants() {
		$this->assertEquals(
			$expected = [
				'NestTreeTypeOak' => 'oak',
				'NestTreeTypeSpruce' => 'spruce'
			],
			$actual = Rosetta::getConstantsForVoClass('Foomo\\Services\\Mock\\Nest')

		);
	}
}