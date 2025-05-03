<?php

require_once __DIR__ . "/../../api/models/connection.php";



class TokenValidator
{
    public static function validate($token)
    {
        $stmt = Connection::connect()->prepare("
            SELECT t.*, u.*
            FROM tokens t
            INNER JOIN users u ON u.id_user = t.id_user_token
            WHERE t.token_token = :token
              AND t.active_token = 1
              AND t.expires_token > NOW()
            LIMIT 1
        ");

        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: false;
    }
}
