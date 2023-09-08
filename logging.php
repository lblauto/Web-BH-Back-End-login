<?php
/* 
Create config.php file and add the configuration code in it
<?php
$db['host'] = "localhost"; // Host name
$db['user'] = "root"; // Mysql username
$db['pass'] = "YourPassword"; // Mysql password
$db['name'] = "YourDBname"; // Database name
?>
*/
// Include configuration
include('config.php');

// Create connection
$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}

if (isset($_POST['login'])) {

     // Get form data
     $email = htmlspecialchars($_POST['email']);
     $pass = htmlspecialchars($_POST['pass']); // Sửa $password thành $pass

     // Prepare the SQL statement
     $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");

     // Check if the statement was prepared successfully
     if ($stmt) {
         mysqli_stmt_bind_param($stmt, 's', $email);

         // Execute the statement
         if (mysqli_stmt_execute($stmt)) {
             $result = mysqli_stmt_get_result($stmt); // Khởi tạo $result
             if (mysqli_num_rows($result) == 1) {
                 $row = mysqli_fetch_assoc($result);
                 if (password_verify($pass, $row['pass'])) { // Đổi $password thành $pass
                     session_start();
                     $_SESSION['user_id'] = $row['id'];
                     header("Location: home-page.php");
                 } else {
                     echo "Invalid password!";
                 }
             } else {
                 echo "User not found!";
             }
         } else {
             echo "Error: " . mysqli_stmt_error($stmt);
         }

         // Close the statement
         mysqli_stmt_close($stmt);
     } else {
         echo "Error preparing statement: " . mysqli_error($conn);
     }

 }

// Close MySQL connection
mysqli_close($conn);

?>
