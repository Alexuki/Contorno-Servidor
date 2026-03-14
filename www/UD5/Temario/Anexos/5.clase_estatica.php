<?php

date_default_timezone_set("Europe/Madrid");

class Fecha {
    private static $calendario = "Calendario gregoriano";
    private static $dias = [
        "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"
    ];
    private static $meses = [
        1 => "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    ];

    public static function getFecha() {
        $indiceDia = (int) date("w");
        $diaMes = (int) date("j");
        $indiceMes = (int) date("n");
        $anio = date("Y");

        return self::$dias[$indiceDia] . " " . $diaMes . " de " . self::$meses[$indiceMes] . " del " . $anio;
    }

    public static function getCalendar() {
        return self::$calendario;
    }

    public static function getHora() {
        return date("H:i:s");
    }

    public static function getFechaHora() {
        return "Hoy es " . self::getFecha() . " y son las " . self::getHora();
    }
}

echo "Usamos el calendario: " . Fecha::getCalendar() . "<br>";
echo Fecha::getFechaHora();

?>