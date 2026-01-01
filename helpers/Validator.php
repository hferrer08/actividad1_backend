<?php

class Validator
{
    // Validaciones básicas

    public static function clean($v): string
    {
        return trim((string)$v);
    }

    public static function id($id, string $fieldName = "id"): int
    {
        if (!isset($id) || $id === "") {
            throw new Exception("❌ Falta el {$fieldName}.");
        }

        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception("❌ El {$fieldName} debe ser mayor a 0.");
        }

        return $id;
    }

    public static function required($value, string $fieldName, int $maxLen = 255, int $minLen = 1): string
    {
        $value = self::clean($value);

        if ($value === "") {
            throw new Exception("❌ El campo {$fieldName} es obligatorio.");
        }

        $len = mb_strlen($value);
        if ($len < $minLen) {
            throw new Exception("❌ El campo {$fieldName} debe tener al menos {$minLen} caracteres.");
        }
        if ($len > $maxLen) {
            throw new Exception("❌ El campo {$fieldName} no puede superar {$maxLen} caracteres.");
        }

        return $value;
    }

    public static function optionalString($value, string $fieldName, int $maxLen = 255, bool $emptyAsNull = true): ?string
    {
        $value = self::clean($value);

        if ($value === "") {
            return $emptyAsNull ? null : "";
        }

        if (mb_strlen($value) > $maxLen) {
            throw new Exception("❌ El campo {$fieldName} no puede superar {$maxLen} caracteres.");
        }

        return $value;
    }

    // Fecha YYYY-MM-DD (opcional)
    public static function optionalDate($value, string $fieldName = "fecha", bool $noFuture = true): ?string
    {
        $value = self::clean($value);

        if ($value === "") return null;

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            throw new Exception("❌ {$fieldName} debe tener formato YYYY-MM-DD.");
        }

        [$y, $m, $d] = array_map('intval', explode('-', $value));
        if (!checkdate($m, $d, $y)) {
            throw new Exception("❌ {$fieldName} no es válida.");
        }

        if ($noFuture && $value > date('Y-m-d')) {
            throw new Exception("❌ {$fieldName} no puede ser futura.");
        }

        return $value;
    }

    // Enteros (requerido / opcional)
    public static function requiredInt($value, string $fieldName, int $min = 1, ?int $max = null): int
    {
        if (!isset($value) || $value === "") {
            throw new Exception("❌ El campo {$fieldName} es obligatorio.");
        }

        if (!is_numeric($value)) {
            throw new Exception("❌ El campo {$fieldName} debe ser numérico.");
        }

        $n = (int)$value;
        if ($n < $min) {
            throw new Exception("❌ El campo {$fieldName} debe ser mayor o igual a {$min}.");
        }
        if ($max !== null && $n > $max) {
            throw new Exception("❌ El campo {$fieldName} no puede ser mayor a {$max}.");
        }

        return $n;
    }

    public static function optionalInt($value, string $fieldName, int $min = 0, ?int $max = null, bool $emptyAsNull = true): ?int
    {
        if (!isset($value)) return $emptyAsNull ? null : 0;

        $value = self::clean($value);
        if ($value === "") return $emptyAsNull ? null : 0;

        if (!is_numeric($value)) {
            throw new Exception("❌ El campo {$fieldName} debe ser numérico.");
        }

        $n = (int)$value;
        if ($n < $min) {
            throw new Exception("❌ El campo {$fieldName} debe ser mayor o igual a {$min}.");
        }
        if ($max !== null && $n > $max) {
            throw new Exception("❌ El campo {$fieldName} no puede ser mayor a {$max}.");
        }

        return $n;
    }

    // Reglas de Series
    public static function yearOptional($value, string $fieldName = "año", int $minYear = 1900): ?int
    {
        $maxYear = (int)date('Y') + 1;
        return self::optionalInt($value, $fieldName, $minYear, $maxYear, true);
    }

    public static function temporadasOptional($value, string $fieldName = "temporadas"): ?int
    {
        return self::optionalInt($value, $fieldName, 1, 100, true);
    }

    // Arrays de IDs (checkbox/multi-select)
    public static function idArray($arr, string $fieldName = "ids", bool $allowEmpty = true): array
    {
        if ($arr === null || $arr === "") {
            if ($allowEmpty) return [];
            throw new Exception("❌ Debe seleccionar al menos un elemento en {$fieldName}.");
        }

        if (!is_array($arr)) {
            throw new Exception("❌ Formato inválido para {$fieldName}.");
        }

        $ids = [];
        foreach ($arr as $v) {
            if ($v === "" || $v === null) continue;

            if (!is_numeric($v)) {
                throw new Exception("❌ {$fieldName} contiene valores inválidos.");
            }

            $id = (int)$v;
            if ($id <= 0) {
                throw new Exception("❌ {$fieldName} contiene IDs inválidos.");
            }

            $ids[] = $id;
        }

        $ids = array_values(array_unique($ids));

        if (!$allowEmpty && count($ids) === 0) {
            throw new Exception("❌ Debe seleccionar al menos un elemento en {$fieldName}.");
        }

        return $ids;
    }
}
