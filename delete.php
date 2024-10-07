<?php
session_start();
// PETER
// Check if the form is submitted and user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_SESSION['userData'])) {
    $username = $_POST['username'];

    // Read the users.txt file into an array
    $users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedUsers = [];

    // Iterate through users and remove the one matching the current username
    foreach ($users as $user) {
        $data = explode(',', $user);
        $storedUsername = trim($data[6]); // Assuming username is at index 6

        // Only add users who don't match the username
        if ($storedUsername !== $username) {
            $updatedUsers[] = $user;
        }
    }

    // Write the updated users back to the file
    file_put_contents('users.txt', implode(PHP_EOL, $updatedUsers) . PHP_EOL);

    // Clear the session and log the user out
    session_destroy();

    // Redirect to homepage with a success message
    echo "<script>alert('Your account has been successfully deleted.'); window.location.href = 'index.html';</script>";
    exit();
} else {
    // If not properly submitted, redirect to homepage
    header("Location: index.html");
    exit();
}
?>
