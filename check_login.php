<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/additional_stylesheet.css">
</head>
<body>
    <?php
        session_start(); 
        include("Include PHP/database_connection.php");

        if (!$conn) {
            echo "Connection failed!";
        }

        if (isset($_POST['email']) && isset($_POST['pass'])) {
            function validate($data){
               $data = trim($data);
               $data = stripslashes($data);
               $data = htmlspecialchars($data);
               return $data;
            }
            $email = validate($_POST['email']);
            $pass = validate($_POST['pass']);
            if (empty($email)) {
                header("Location: index.php?error=Admin Login: User Name is required");
                exit();
            }else if(empty($pass)){
                header("Location: index.php?error=Admin Login: Password is required");
                exit();
            }else{
                $simple_string = $pass."\n";
                // Storingthe cipher method
                $ciphering = "AES-128-CTR";
                // Using OpenSSl Encryption method
                $iv_length = openssl_cipher_iv_length($ciphering);
                $options = 0;
                // Non-NULL Initialization Vector for encryption
                $encryption_iv = '1234567891011121';
                // Storing the encryption key
                $encryption_key = "W3docs";
                // Using openssl_encrypt() function to encrypt the data
                $pass = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
                                
                $sql = "SELECT * FROM administration_users WHERE 
                        email='$email' AND password='$pass'";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row['email'] === $email && $row['password'] === $pass) {
                        echo "Logged in!";
                        $email = $row['email'];
                        $_SESSION['email'] = $row['email'];
                        header("Location: home.php");
                        exit();      
                    }else{
                        header("Location: index.php?error=Admin Login: Incorect User name or password");
                        exit();
                    }
                }else{
                    header("Location: index.php?error=Admin Login: Incorect User name or password");
                    exit();
                }
            }
        }else{
            header("Location: index.php");
            exit();
        }
    ?>