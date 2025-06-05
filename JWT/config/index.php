<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Allow: GET, POST, OPTIONS");

// Manejar preflight (opcional, pero recomendado)
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    exit();
}

date_default_timezone_set("America/Matamoros");

require_once "conexion.php";
require_once "enviarCorreo.php";
# mkdir firebase-php-jwt
# cd firebase-php-jwt
# composer require firebase/php-jwt
require_once "firebase-php-jwt/vendor/autoload.php";
require_once "kreait-firebase-php/vendor/autoload.php";

$jwtKey = "Test12345";

$serviceAccountJson = json_decode('{
  "type": "service_account",
  "project_id": "actividad-50524",
  "private_key_id": "04d65b426a583d9b52abeff1e5898fe841e5aab2",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCiZQmv7K5qaPBy\nX8zkIka2nwZRDCHdLROjMxy5mJMuheqoaMpEqF+GNXM+fnAfF8HOV9c7mSLEx0rA\nPMKdyBprVOzblxULg/TuumFGeiOSPyQ7qL9OMakxQC7Xejv3H6vV5onLw8pYbO4k\nrgJtE59QbEw6loFhm0eY3DOXak4vvACzm+aBRoL0czLTUmTfQoLQ8itp1Mw8V2Al\nbTueHIPyn5Uch0N4C7nWEQfrO6+RjW9r9VdNm31VE8m9D+8FYClWXBxqYBspStMX\n/23jqWfybsAaToxndOyv3TTwaT42CbHUzB6IDckyYn4JrXweZgNLpAJMdehwscar\n7Y6m9o+3AgMBAAECggEAGQ05J1h9t5CktyAsPW1EN1vk34/dkX8orl5uKfttiRnD\nj2NLALhwtAV+178tkL5beXd3jowbHvwXffxhkXGTEceyGlX+Ox6KJGHAK90fq6nt\n7v4jNO7YSO67yyXiJkdOfNrpmvODPYzgV+w4F5eVVhS+1bLuFp6btusRUmRl11sM\nJY/f628VRDMEaIe65hyESXFRb61LKX4Q76x6+y4VkxmBrD2YxhkVf6qfNcNtfKRj\nZtSvZ+DVZcQp0xTZawDUyXeN8PbDiAVZPE1G6e51l11QbJW5aus5e1O2rIK6jnwY\nRIX9mC80BWYzqcsSIgYtDAfphueB7ziUKsRjWT3xEQKBgQDeDAu4RezXTx2Iro03\nBsl0GyNqSZMveuUnL3Nit432Ms7/tFSPtSHZaVDqWNEABKu5yRfhYyiS4TZLlaVE\ndPLWD8lfmTnJeCXHgq9yXjatnIpdquijW/tsc71rXYQapud6BXZyA2GlSYGJsptX\nsi6hzkkS+n35e4D9zKBklsGQgwKBgQC7Oeu0muNZ0zW+g3gQegni2ryALTPQ8tez\nOC5Y7z2OepvyMPcR3Rl9wN2EURI7RbtFOonX2FAKi4bNbeUBxmf2Y5U76ui5im5l\nIPj/cNo0Feg9HSZ0ZdshXd9rkynVlsOmCz0b7aKQRTH0y8jDNhI7gJJzYFMrtfmm\nZqfp24F1vQKBgQDIOWR4DdW7cQPYtE1ySRRvNemBdkbakZ8A7rDFW28PilFOEnYN\n/+899NGZ/+y2b3/KT6LRAnEbwSkMjywvxqUSkbmsEqchHM40UYuTPZFgi7/ZJrwQ\nUmktKlCr9++feNFSHorn1FTAjr/YQs4BDhMupK0QCACAbvWp5lLbYXW5sQKBgGU/\neGDt1f7wgkLps9ctOAVvBrtGNpxLhyxLOH0tu5s+YPEDW8lUSQHBd8mjUfmtxw00\nD/Ei8H4TYC3dvRdNpVEQH1cMvRgBbZvQzfbNn6LFKhdKmU4e+va7XiQ9rETuSBWW\nRFDDHMNqOA7K2WH/7rIw4IH2WGPt//jl0O5dn0/9AoGBAIW2BBVodQznqDNCMwa8\nqhQSCbyhwaq8nNbQTgxDQhikUtIifLnRgTaz4l7ihCMywOgtuM4lkT21L7RPUvjd\n43w7BREZPsceUNYZyl/QlK4ImqLh3eiM0paZZhz9Ba1GGIqTlG9jo5Zy0fpJ4Ahh\nmo5snb+tJ8FTsukkCHKN0Wad\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk-fbsvc@actividad-50524.iam.gserviceaccount.com",
  "client_id": "110180745476108409728",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40actividad-50524.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
', true);

$firebase = (new Kreait\Firebase\Factory)->withServiceAccount($serviceAccountJson)->createMessaging();

$con = new Conexion(array(
    "tipo"       => "mysql",
    "servidor"   => "caboose.proxy.rlwy.net",
    "bd"         => "railway",
    "usuario"    => "root",
    "port"       => "44486",
    "contrasena" => "LtnSDbWbdaJCyOhqZreXJSMbKggVVTod"
));

?>
