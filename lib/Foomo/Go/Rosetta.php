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
use Foomo\Services\Reflection\ServiceObjectType;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Rosetta
{
	public static function getRecursionsInType($type, &$types = [], array &$recursions = [])
	{
		if(is_string($type)) {
			$type = new ServiceObjectType($type);
		}
		if($type->isComplex()) {
			if(in_array($type->type, $types)) {
				$recursions[] = $type->type;
			} else {
				$types[] = $type->type;
				foreach($type->props as $propType) {
					self::getRecursionsInType($propType, $types, $recursions);
				}
			}
		}
		return $recursions;
	}

	public static function phpVoToGoStructSource($voClassName, array &$withKnownClassNames)
	{
		if(in_array($voClassName, $withKnownClassNames)) {
			$cleanKnownClasses = [];
			foreach($withKnownClassNames as $knownClassName) {
				if($voClassName != $knownClassName) {
					$cleanKnownClasses[] = $knownClassName;
				}
			}
			$withKnownClassNames = $cleanKnownClasses;
		}
		$type = new ServiceObjectType($voClassName);
		$src = '// from php class ' . $type->type . PHP_EOL;
		self::addComment($type->phpDocEntry, $src, 0);
		self::addStruct('type ' . self::getGoStructName($voClassName), $src, $type, $withKnownClassNames, 0);
		$withKnownClassNames[] = $type->type;
		return $src;
	}
	private static function getGoStructName($className)
	{
		$parts = explode('\\', $className);
		return end($parts);
	}
	private static function addStruct($name, &$src, ServiceObjectType $type, &$withKnownClassNames, $indent)
	{
		$prefix = ($indent > 0 ? ucfirst($name) :  $name) . ' ' . ($type->isArrayOf ? '[]' : '');
		if(in_array($type->type, $withKnownClassNames)) {
			self::addLineToSrc(
				$src,
				$prefix . '*' . self::getGoStructName($type->type),
				$indent
			);
		} else {
			self::addLineToSrc($src, $prefix . 'struct {', $indent);
			self::addFieldsToStruct($src, $type, $withKnownClassNames, $indent + 1);
			self::addLineToSrc($src, '} ' . ($indent > 0 ? self::getJSONTag($name) : '' ) , $indent);
		}
	}
	private static function addFieldsToStruct(&$src, ServiceObjectType $type, &$withKnownClassNames, $indent)
	{
		foreach($type->props as $propName => $prop) {
			self::addComment($prop->phpDocEntry, $src, $indent);
			//$propName = ucfirst($propName);
			$line =  ucfirst($propName) . ' ';
			if($prop->isArrayOf) {
				$line .= '[]';
			}
			if(!$prop->isComplex()) {
				$line .= self::plainGoType($prop->plainType) . ' ' . self::getJSONTag($propName);
				self::addLineToSrc($src, $line, $indent);
			} else {
				self::addStruct($propName, $src, $prop, $withKnownClassNames, $indent + 1);
			}
		}
	}
	private static function getJSONTag($name)
	{
		return '`json:"' . $name . '"`';
	}
	private static function addComment($phpDocEntry, &$src, $indent)
	{
		if($phpDocEntry && !empty($phpDocEntry->comment)) {
			foreach(explode(PHP_EOL, $phpDocEntry->comment) as $commentLine) {
				self::addLineToSrc($src, '// ' . $commentLine, $indent);
			}
		}
	}
	private static function addLineToSrc(&$src, $text, $indent)
	{
		$src .= str_repeat('	', $indent) . $text . PHP_EOL;
	}
	private static function plainGoType($plainType)
	{
		switch($plainType) {
			case 'string':
				return 'string';
			case 'int':
			case 'integer':
				return 'int64';
			case 'double': // really ?
				return 'float64';
			case 'float':  // really ?
				return 'float32';
			case 'bool':
			case 'boolean':
				return 'bool';
			default:
				return 'interface{}';
		}
	}
}