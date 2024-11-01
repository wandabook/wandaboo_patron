<?php

class API {
    private $base_url;


    public function __construct($base_url) {
        $this->base_url = rtrim($base_url, '/');
    }

    private function callAPI($method, $endpoint, $data = null, $api_key, $api_user) {
        $url = $this->base_url . '/' . ltrim($endpoint, '/');

        $curl = curl_init($url);

        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data !== null) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data !== null) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                // 'GET' request
                if ($data !== null) {
                    $url = sprintf('%s?%s', $url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "x-api-key: ".$api_key,
            "x-api-user: ".$api_user
            // Ajoutez d'autres en-têtes d'authentification ou personnalisés ici si nécessaire
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception("Erreur cURL : " . $error);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON : " . json_last_error_msg());
        }

        return $decoded;
    }

    public function get($endpoint, $data = null,$api_key, $api_user) {
        return $this->callAPI('GET', $endpoint, $data,$api_key, $api_user);
    }

    public function post($endpoint, $data = null,$api_key, $api_user) {
        return $this->callAPI('POST', $endpoint, $data,$api_key, $api_user);
    }

    public function put($endpoint, $data = null,$api_key, $api_user) {
        return $this->callAPI('PUT', $endpoint, $data,$api_key, $api_user);
    }

    public function delete($endpoint,$api_key, $api_user) {
        return $this->callAPI('DELETE', $endpoint,$api_key, $api_user);
    }
     // Méthode spécifique pour envoyer un fichier
     public function uploadFile($endpoint, $file_path) {
        $url = $this->base_url . '/' . ltrim($endpoint, '/');
        $file = new CURLFile($file_path);

        $post_fields = array(
            'file' => $file,
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
            
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception("Erreur cURL : " . $error);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON : " . json_last_error_msg());
        }

        return $decoded;
    }
}
