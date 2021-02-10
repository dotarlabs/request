<?php

namespace Src;

class Request
{
        
    /**
     * get Devuelve array de respuesta obtenida por el webservice externo
     *
     * @param  mixed $url url del recurso externo
     * @param  mixed $config configuracion custom de CURL
     * @param  mixed $headers por default es vacio
     * @return array
     */
    public static function get($url, $config = [], $headers = []) : array
    {
    
        if (count($config) > 0) {
            foreach($config as $key => $value) {
                $params[$key] = $value;
            }
        }

        $params = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        ];
        
        $response = self::init($params);

        $json = json_decode(self::remove_utf8_bom($response), true);
        return $json;
    }
    
    /**
     * post Devuelve array de respuesta obtenida por el webservice externo
     *
     * @param  mixed $url url del recurso externo
     * @param  mixed $pData postfields debe ser JSON
     * @param  mixed $config configuracion custom de CURL
     * @param  mixed $headers por default es vacio
     * @return array
     */
    public static function post($url, $pData, $config = [], $headers = []) : array
    {
        
 		if (count($config) > 0) {
			foreach($config as $key => $value) {
				$params[$key] = $value;
			}
        }

        $params = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($pData),
            CURLOPT_HTTPHEADER => $headers
        ];

        $response = self::init($params);

        $json = json_decode(self::remove_utf8_bom($response), true);
        return $json;
    }

    private static function init(array $params)
    {
        
        $ch = curl_init();
        $cParams = count($params);

        $params[CURLOPT_RETURNTRANSFER] = 1; // by default is enabled

        if ($cParams > 0) {
            for ($i = 0; $i < $cParams; $i++) { 
                curl_setopt_array($ch, $params);
            }
        } else {
            trigger_error("cURL #: no config supplied.");
        }
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        
		curl_close($ch);

        if ($err) {
            trigger_error("cURL Error #: {$err}");
        } else {
            return $response;
        }
    }

    private static function remove_utf8_bom($text) {
        $bom  = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }
}