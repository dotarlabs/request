<?php

namespace DotarHttp;

class Request
{
    
    static $logger;

    /**
     * get Devuelve array de respuesta obtenida por el webservice externo
     *
     * @param  mixed $url url del recurso externo
     * @param  mixed $config configuracion custom de CURL
     * @param  mixed $headers por default es vacio
     * @return array
     */
    public static function get($url, $config = [], $headers = [], $isXml = false)
    {

        $params = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        ];

        if (count($config) > 0) {
            foreach($config as $key => $value) {
                $params[$key] = $value;
            }
        }
        
        $response = self::init($params);

        self::$logger = new \Monolog\Logger('DotarHttp\Request\GET');
        
        switch ($response['code']) {
            case 28:
                return $response;
                break;
            case 200:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_200.log", \Monolog\Logger::INFO));

                if (!$isXml) {
                    self::$logger->info("200", [$response]);
                    return json_decode(self::remove_utf8_bom($response['response']), true);
                } else {
                    self::$logger->info("200", [$response]);
                    return simplexml_load_string($response['response']);
                }

                break;
            case 201:

                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_201.log", \Monolog\Logger::INFO));

                if (!$isXml) {
                    self::$logger->info("201", [$response]);
                    return json_decode(self::remove_utf8_bom($response['response']), true);
                } else {
                    self::$logger->info("201", [$response]);
                    return simplexml_load_string($response['response']);
                }

                break;
            case 400:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_400.log", \Monolog\Logger::ERROR));
                self::$logger->error("400", [$response]);

                break;
            case 401:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_401.log", \Monolog\Logger::ERROR));
                self::$logger->error("401", [$response]);

                break;
            case 402:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_402.log", \Monolog\Logger::ERROR));
                self::$logger->error("402", [$response]);

                break;
            case 403:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_403.log", \Monolog\Logger::ERROR));
                self::$logger->error("403", [$response]);

                break;
            case 404:

                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_404.log", \Monolog\Logger::ERROR));
                self::$logger->error("404", [$response]);

                break;
            case 500:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_500.log", \Monolog\Logger::ERROR));
                self::$logger->error("500", [$response]);

                break;
            case 501:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_501.log", \Monolog\Logger::ERROR));
                self::$logger->error("501", [$response]);

                break;
            case 502:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_502.log", \Monolog\Logger::ERROR));
                self::$logger->error("502", [$response]);

                break;
            case 503:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/get/request_503.log", \Monolog\Logger::ERROR));
                self::$logger->error("503", [$response]);

                break;
            case 504:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/request_504.log", \Monolog\Logger::ERROR));
                self::$logger->error("504", [$response]);

                break;
            default:
                echo "cURL error #:\t" . curl_error($ch) . "\n" . "Unexpected HTTP Code #:\t" . $http_code . " \n";
                exit(1);
        }
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
    public static function post($url, $pData, $config = [], $headers = [], $isBody = false) : array
    {

        $params = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => !$isBody ? http_build_query($pData) : json_encode($pData),
            CURLOPT_HTTPHEADER => $headers
        ];
        
 		if (count($config) > 0) {
			foreach($config as $key => $value) {
				$params[$key] = $value;
			}
        }

        $response = self::init($params);

        self::$logger = new \Monolog\Logger('DotarHttp\Request\POST');
        
        switch ($response['code']) {
            case 28:
                return $response;
                break;
            case 200:
                $json = json_decode(self::remove_utf8_bom($response['response']), true);

                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_200.log", \Monolog\Logger::INFO));
                self::$logger->info("200", [$json]);

                return $json;

                break;
            case 201:
                $json = json_decode(self::remove_utf8_bom($response['response']), true);

                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_201.log", \Monolog\Logger::INFO));
                self::$logger->info("201", [$json]);

                return $json;

                break;
            case 400:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_400.log", \Monolog\Logger::ERROR));
                self::$logger->error("400", [$response]);

                break;
            case 401:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_401.log", \Monolog\Logger::ERROR));
                self::$logger->error("401", [$response]);

                break;
            case 402:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_402.log", \Monolog\Logger::ERROR));
                self::$logger->error("402", [$response]);

                break;
            case 403:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_403.log", \Monolog\Logger::ERROR));
                self::$logger->error("403", [$response]);

                break;
            case 404:

                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_404.log", \Monolog\Logger::ERROR));
                self::$logger->error("404", [$response]);

                break;
            case 500:
                
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_500.log", \Monolog\Logger::ERROR));
                self::$logger->error("500", [$response]);

                break;

            case 501:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_501.log", \Monolog\Logger::ERROR));
                self::$logger->error("501", [$response]);

                break;

            case 502:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_502.log", \Monolog\Logger::ERROR));
                self::$logger->error("502", [$response]);

                break;

            case 503:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_503.log", \Monolog\Logger::ERROR));
                self::$logger->error("503", [$response]);

                break;

            case 504:
            
                self::$logger->pushHandler(new \Monolog\Handler\StreamHandler("./log/request/post/request_504.log", \Monolog\Logger::ERROR));
                self::$logger->error("504", [$response]);

                break;
            default:
                echo "cURL error #:\t" . curl_error($ch) . "\n" . "Unexpected HTTP Code #:\t" . $http_code . " \n";
                exit(1);
        }
    }

    private static function init(array $params)
    {
        
        $ch = curl_init();
        $cParams = count($params);

        $params[CURLOPT_RETURNTRANSFER] = 1;

        if ($cParams > 0) {
            for ($i = 0; $i < $cParams; $i++) { 
                curl_setopt_array($ch, $params);
            }
        } else {
            trigger_error("cURL #: no config supplied.");
        }
        
        $response = curl_exec($ch);
        
        if (!curl_errno($ch)) {
            return [
                'error'    => false,
                'code'     => curl_getinfo($ch, CURLINFO_HTTP_CODE),
                'response' => $response,   
            ];
        } else {
            return [
                'error'   => true,
                'code'    => curl_errno($ch),
                'message' => curl_error($ch)
            ];
        }

        curl_close($ch);
    }

    private static function remove_utf8_bom($text) {
        $bom  = pack('H*','EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }
}