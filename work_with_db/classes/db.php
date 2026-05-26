<?php

class DB {

    public static function connect() {

        return new mysqli(
            "127.0.0.1",
            "wp1_semenova92",
            "2n4DWEs7",
            "wp1_semenova92"
        );
    }
}
?>