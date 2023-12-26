<?php

namespace UDAdsManager\Core\DFPAdSize;

abstract class AdSize implements \JsonSerializable
{
    public static function compare(Adsize $a, Adsize $b)
    {
        return $a->getComparableValue() - $b->getComparableValue();
    }

    public function compareTo(Adsize $object)
    {
        return $this->getComparableValue() - $object->getComparableValue();
    }

    abstract public function getComparableValue(): int;
}
