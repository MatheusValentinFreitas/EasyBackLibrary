<?php
class Filter
{
    static function formataString(string $string): string
    {
        return $string = preg_replace('/\s+/', ' ', trim($string));
    }

    static function stringLimiteTam(string $string, int $limite): bool
    {
        if (strlen($string) <= $limite) {
            return true;
        } else {
            return false;
        }
    }

    static function stringLimiteExatIgual(string $string, int $limite): bool
    {
        if (strlen($string) == $limite) {
            return true;
        } else {
            return false;
        }
    }

    static function retornaCampoNumerico(float $float)
    {
        return $float;
    }

    static function retornaCampoTratado(mixed $string, int $limTam = null, int $limExt = null, string $campoNome, $formataString = true)
    {
        $result = true;
        $message = '';

        try {

            if (empty($string)) {
                // throw new Exception("O campo $campoNome deve ser preenchido.");
                $string = NULL;
            }

            if ($formataString && $string != null) {
                $string = self::formataString($string);
            }

            if ($limTam != null) {
                if (!self::stringLimiteTam($string, $limTam)) {
                    throw new Exception("O campo $campoNome ultrapassa o limite de $limTam caracteres.");
                }
            }

            if ($limExt != null) {
                if (!self::stringLimiteExatIgual($string, $limExt)) {
                    throw new Exception("O campo $campoNome deve ter exatamente $limExt caracteres.");
                }
            }

            $message = "Tudo OK";
        } catch (Exception $e) {
            $result = false;
            $message = $e->getMessage();
        }

        return array('result' => $result, 'message' => $message, 'string' => $string);
    }
}
