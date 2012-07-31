<?php
/*
 * Class Factory
 * This is a object factory that creates arbitrary objects
 * Factory will throw exception if no such class
 * @param string $className
 * @param object $params 
 * @return object
 * @author Roy
 */
class Factory {
	public static function create($className, $params = NULL) {
		if(class_exists($className)) {
			if($params == NULL)	
				return new $className();
			else {
				$obj = new ReflectionClass($className);
				return $obj->newInstanceArgs($params);
			}	
		}
		
		throw new Exception("Class [ $className ] not found...");
	}

	/*
	 * This is the autoload, so no need to require all classes
	 * And it throws exception if there's no such file
	 * @param string $className
	 * @author Roy
	 */	
	public static function autoload($className) {
	if(file_exists($file = "$className.crud.php"))
		require_once $file;
	elseif(file_exists($file2 = "./class/$file"))
		require_once $file2;
	elseif(file_exists($file3 = "./model/$file"))
		require_once $file3;	
	else
		throw new Exception("File [ $className.crud.php ] not found...");		
	}
}


?>