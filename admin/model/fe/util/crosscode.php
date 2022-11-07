<?php
class ModelFeUtilCrosscode extends Model {

    public function normalize($crosscode) {
        $normalized = preg_replace('(#|_|\s|-|\.)', '', $crosscode);
        return $normalized;
    }

}
