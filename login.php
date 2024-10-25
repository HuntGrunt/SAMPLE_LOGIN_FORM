<?php
session_start();

// Path to the CSV file
$csvFile = 'users.csv';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username_or_email']) || empty($_POST['password'])) {
        echo "Please fill in all required fields.";
    } else {
        $usernameOrEmail = filter_var($_POST['username_or_email'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the CSV file exists and is readable
        if (is_readable($csvFile)) {
            // Open the CSV file for reading
            if (($file = fopen($csvFile, 'r')) !== FALSE) {
                $loginSuccess = false;
                while (($userData = fgetcsv($file)) !== FALSE) {
                    $id = $userData[0];
                    $username = $userData[1];
                    $email = $userData[2];
                    $hashedPassword = $userData[3];
                    $lastname = $userData[4];
                    $firstname = $userData[5];
                    $middlename = $userData[6];
                    $dob = $userData[7];
                    $contact = $userData[8];
                    $role = $userData[9];

                    // Check if the username or email matches
                    if (($usernameOrEmail == $username || $usernameOrEmail == $email) && password_verify($password, $hashedPassword)) {
                        // Store user data in the session
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['middlename'] = $middlename;
                        $_SESSION['email'] = $email;
                        $_SESSION['dob'] = $dob;
                        $_SESSION['contact'] = $contact;
                        $_SESSION['role'] = $role;

                        // Redirect based on user role
                        if ($role == 'admin') {
                            header("Location: glc_aboutADMIN.html");
                        } elseif ($role == 'student') {
                            header("Location: glc_aboutSTUDENT.html");
                        }
                        $loginSuccess = true;
                        exit();
                    }
                }
                fclose($file);
                
                if (!$loginSuccess) {
                    echo "Incorrect username or password.";
                }
            } else {
                echo "Error reading the user data file.";
            }
        } else {
            echo "User data file is not accessible.";
        }
    }
}
?>
