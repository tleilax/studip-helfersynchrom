<?php
abstract class SynchronizableObject
{
    abstract static function findAll($local, $since = 0, $version = null);
    abstract static function getLatestStudipVersion($language = null);

    abstract function updateInfo($version, $installation_id);
    abstract function replace(SynchronizableObject $new_object);
    abstract function remove();

    abstract function getTitle();

    abstract function getValue($key);
    abstract function setValue($key, $value);
    abstract function toArray();
    

    public function diff(SynchronizableObject $other_object)
    {
        if (get_class($this) !== get_class($other_object)) {
            throw new Exception('Object classes differ.');
        }
        
        $data0 = $this->toArray();
        $data1 = $other_object->toArray();
        
        return $this->compareArray($this->toArray(), $other_object->toArray());
    }
    
    protected function compareArray($array0, $array1, $path = array())
    {
        $differences = array();

        foreach ($array0 as $index => $value0) {
            $value1 = $array1[$index];

            if (gettype($value0) !== gettype($value1)) {
                $differences[join('/', $path)] = 'Type mismatch';
            } elseif (is_array($value0)) {
                $differences = array_merge($differences, $this->compareArray($value0, $value1, $path + array($index)));
            } elseif ($value0 != $value1) {
                
            }
        }

        return $differences;
    }
}
