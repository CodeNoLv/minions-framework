<?php

    class Authorization
    {
        protected $password;

        /*
            Description: Initiates Authorization class object
            Parameters: null
            Return: (object Authorization) Initiated Authorization object
        */
        public static function init()
        {
            return new Authorization();
        }

        /*
            Description: Converts users entered password to hashpassword
            Parameters: required (String) $password - users entered password which will be hashed
            Return: (String) Hashed $password
        */
        public static function hashPassword($password)
        {
            $options = array(
                'cost' => 10,
                'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            );
    
            return password_hash($password, PASSWORD_BCRYPT, $options);
        }

        /*
            Description: Checks users entered password with on registration hashed password 
            Parameters: required (String) $password - users entered password which will be hashed
                        required (String) $hashedPassword - on registration hashed password 
            Return: (Boolean) Indicates if users entered password and hashed password matches and user can be logged in 
        */
        public static function checkPassword($password, $hashedPassowrd)
        {
            if (password_verify($password, $hashedPassowrd)) return true;
            return false;
        }

        /*
            Description: Benchmarks server to determene affordable PASSWORD_BCRYPT cost and logs it
            Parameters: null
            Return: (Integer) Affordable PASSWORD_BCRYPT cost
        */
        public static function passwordHashBenchamrk()
        {
            $timeTarget = 0.05;
            $cost = 8;

            do
            {
                $cost++;
                $start = microtime(true);
                password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
                $end = microtime(true);
            } while (($end - $start) < $timeTarget);

            logger::addLog("framework core Authorization passwordHashBenchamrk", "Appropriate Cost Found: " . $cost);
            return $cost;
        }
    }

?>