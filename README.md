# foomo - golang integration module

This foomo php module is designed to work with the [gofoomo Go package](https://github.com/foomo/gofoomo) .

## Generate Go structs for php value objects

´´´php

// typically the make method of a foomo module class is a good place to generate code

public static function make($target, \Foomo\Modules\MakeResult $result)
{
    switch($target) {
        case 'all':
            // typically point to you go path 
            $goPath = self::getBaseDir('go');
            if(file_exists($goPath) && is_dir($goPath) && is_writable($goPath)) {
                // this call will generate gopath/src/github.com/foomo/gofoomo/services/rpc/value_objects.go
                \Foomo\Go\Utils::writeStructsForValueObjects(
                    [
                        'Foomo\\Services\\RPC\\Protocol\\Call\\MethodCall',
                        'Foomo\\Services\\RPC\\Protocol\\Reply\\MethodReply'
                    ],
                    $package = 'github.com/foomo/gofoomo/services/rpc',
                    $goPath
                );
                $result->addEntry('wrote go value object sources');
            } else {
                $result->addEntry('can not write go value object sources');
            }
            break;
        default:
            parent::make($target, $result);
    }
}
´´´


