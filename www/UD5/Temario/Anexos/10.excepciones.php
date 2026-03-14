<?php
    class ExPropia extends Exception {
    }

    class ExPropiaClass {
        public static function testNumber($number) {
            if($number == 0) {
                throw new ExPropia("El número es 0!");
            }
            echo "Número: $number <br>";
        }
    }

    try {
        ExPropiaClass::testNumber(1);
        ExPropiaClass::testNumber(-5);
        ExPropiaClass::testNumber(0);
    } catch (ExPropia $e) {
        echo "Excepción capturada: " . $e->getMessage();
    }
?>