<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('Database.php');
require('Config.php');

class Register
{
    use Database;

    /**
     * Validates email using Abstract API.
     *
     * @param string $email The email address to validate.
     * @return bool True if the email is valid, false otherwise.
     */
    public function emailValidate($email)
    {
        // Validate email.
        if (empty($email)) {
            echo "<p>Email is required!</p>";
            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Invalid email format!</p>";
            return false;
        }

        // Abstract API key
        $api_key = "e31bd28cef67416db9e40c5101f170ac";
        $endpoint = "https://emailvalidation.abstractapi.com/v1/?api_key=e31bd28cef67416db9e40c5101f170ac&email=$email";

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            echo "<p>cURL error: " . curl_error($ch) . "</p>";
            curl_close($ch);
            return false;
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $data = json_decode($response, true);

        if (isset($data['is_valid_format']) && isset($data['is_smtp_valid']['value']) && isset($data['autocorrect'])) {
            if ($data['is_valid_format'] && $data['is_smtp_valid']['value'] && $data['autocorrect'] === '') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Inserts user data into the database.
     *
     * @param string $fname The user's first name.
     * @param string $lname The user's last name.
     * @param string $username The user's username.
     * @param string $email The user's email address.
     * @param string $password The user's hashed password.
     * @return string The result message.
     */
    public function insertUserData($fname, $lname, $username, $email, $password)
    {
        $this->conn->begin_transaction();

        $sql = "INSERT INTO user (first_name, last_name, Username, hashed_password, email) VALUES ('$fname', '$lname', '$username', '$password', '$email')";

        try {
            // Execute queries
            $query1 = $this->conn->query($sql);

            // Check if all queries were successful
            if ($query1) {
                // Commit transaction
                $this->conn->commit();
                $_SESSION['email'] = $email;
                return "Registered Successfully";
            } else {
                // Rollback transaction
                $this->conn->rollback();
                return "Error: " . $this->conn->error;
            }
        } catch (\mysqli_sql_exception $e) {
            $this->conn->rollback();
            if ($e->getMessage() == "Duplicate entry '$username' for key 'user.PRIMARY'") {
                return "Already Registered with this username";
            } else if ($e->getMessage() == "Duplicate entry '$email' for key 'user.email'") {
                return "Already Registered with this email";
            }
            return "Error: " . $e->getMessage();
        }
    }
}

$register = new Register();
if (isset($_POST['submit'])) {
    $register->connection(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
    // Get form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST['confirm_password'];
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    if ($password != $confirm_password) {
        echo '<p style="color:red">Password is not the same</p>';
        exit;
    }

    $email = $_POST['email'];
    if ($register->emailValidate($email) == true) {
        $result = $register->insertUserData($fname, $lname, $username, $email, $password_hashed);
    } else {
        echo "<script>alert('Email not valid');  window.location.href ='signup.php';</script>";
    }
    // Output the result
    if ($result == 'Registered Successfully') {
        header('Location: /');
    } else {
        echo "<script>alert('$result');  window.location.href ='signup.php';</script>";
    }

    // Close the database connection
    $register->closeConnection();
}
