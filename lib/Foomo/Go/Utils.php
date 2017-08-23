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
use Foomo\Modules\Resource\Fs;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Utils extends \Foomo\Modules\ModuleBase
{
	/**
	 * parses value objects and generates go source files in a given source directory
	 *
	 * @param string[] $voClassNames php class names
	 * @param string $goPackage go package name
	 * @param string $goPath go path
	 * @param string[] $imports go imports
	 */
	public static function writeStructsForValueObjects(array $voClassNames, $goPackage, $goPath, $imports = [])
	{

		$goFile = $goPath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $goPackage . DIRECTORY_SEPARATOR . 'value_objects.go';
		Fs::getAbsoluteResource(Fs::TYPE_FOLDER, dirname($goFile))->tryCreate();
		$recursions = [];
		foreach($voClassNames as $voClassName) {
			$recursions = array_unique(array_merge($recursions, Rosetta::getRecursionsInType($voClassName)));
		}
		$voClassNames = array_unique(array_merge($voClassNames, $recursions));

		$src = "";

		foreach($voClassNames as $voClassName) {
			$constants = Rosetta::getConstantsForVoClass($voClassName);
			if(!empty($constants)) {
				$src .= '// constants from ' . $voClassName . PHP_EOL;
				$src .= Rosetta::goConstantsToSource($constants) . PHP_EOL;
			}
			$src .= Rosetta::phpVoToGoStructSource($voClassName, $voClassNames, $imports) . PHP_EOL;
		}
		$packageParts = explode('/', $goPackage);
		$head = 'package ' . end($packageParts) . PHP_EOL;

		if(count($imports) > 0) {
			$head .= PHP_EOL .
				'import(' . PHP_EOL
			;
			foreach($imports as $import) {
				$head .= '	"' . $import . '"' . PHP_EOL;
			}
			$head .= ')' . PHP_EOL;
		}
		file_put_contents($goFile, $head . PHP_EOL . PHP_EOL. $src);
		$cmd = \Foomo\CliCall::create('gofmt', ['-w=true', $goFile]);
		$cmd->execute();
	}
}