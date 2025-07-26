<?php
namespace app\controller;

require_once $_SERVER['DOCUMENT_ROOT'] .'/class/route.php';
route('dbconfig', 'class');
route('userActivityLogger', 'class');
    
    
    function redirectToHomePage(){
        header("location: https://www.pefcarbonsink.info/");
        exit();
    }
    
    function redirectToLoginPage(){
        header("location: https://www.pefcarbonsink.info/login/");
        exit();
    }

class userInfo extends dbh { 
    
    public function userLoginStatus($userlogin){
        
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d h:i A');
        
        $sql = "SELECT status, username FROM userActivity WHERE username = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $userlogin, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount()> 0){
            $result = $stmt->fetch();
            $status = $result['status'];
            $statusUserName = $result['username'];
            
            if ($userlogin === $statusUserName){
                
                if ($status === 'Offline'){
                    $online = "Online";
                    $sql = "UPDATE userActivity SET status = ?, loginTime = ? WHERE username = ? LIMIT 1"; 
                    $stmt = $this->connect()->prepare($sql);
                    $stmt->bindParam(1, $online, PDO::PARAM_STR);
                    $stmt->bindParam(2, $date, PDO::PARAM_STR);
                    $stmt->bindParam(3, $userlogin, PDO::PARAM_STR);
                    
                    if($stmt->execute()){
                        $message = 'Status updated sucess 1 -> '. $userlogin;
                        userActivityLogs($message);
                    }else{
                        $message = 'Status updated failed 1 -> '. $userlogin;
                        userActivityLogs($message);
                    }
                }elseif ($status === 'Online'){
                    $sql = "UPDATE userActivity SET loginTime = ? WHERE username = ? LIMIT 1"; 
                    $stmt = $this->connect()->prepare($sql);
                    $stmt->bindParam(1, $date, PDO::PARAM_STR);
                    $stmt->bindParam(2, $userlogin, PDO::PARAM_STR);
                    
                    if($stmt->execute()){
                        $message = 'Status updatw sucess 2 -> '. $userlogin;
                        userActivityLogs($message);
                    }else{
                        $message = 'Status update failed 2 -> '. $userlogin;
                        userActivityLogs($message);
                    }
                }else{
                    $status = 'Online';
                    $sql = "INSERT INTO userActivity(username, status, loginTime) VALUES (?, ?, ?)";
                    $stmt = dbh::connect()->prepare($sql);
                    $stmt->bindParam(1, $userlogin, PDO::PARAM_STR);
                    $stmt->bindParam(2, $status, PDO::PARAM_STR);
                    $stmt->bindParam(3, $date, PDO::PARAM_STR);
                    
                    if ($stmt->execute()){
                        //echo $userlogin . 'is login on' . $date;
                        //echo "<script>alert('status update!');</script>";
                        $message = 'Status update: new added user to activityLogs success 3 -> ' . "$userlogin";
                        userActivityLogs($message);
                    }else{
                        //echo 'update status error!'; 
                        //echo "<script>alert('update status error!');</script>";
                        $message = 'Status update: attempt to add user ' ."$userlogin". ' to activityLogs failed 3';
                        userActivityLogs($message);
                    }
                } 
                
            }else{
                $message = 'Status update error -> ' . $userlogin . 'is not registered ';
                userActivityLogs($message);
            }
        }else{
            $message = 'Status update error -> ' . $userlogin . 'is not registered ';
            userActivityLogs($message);
        }
    }
    
    public function loginUser($loginUsername, $password){
        try {
            $sql = "SELECT * FROM users WHERE user_name = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(1, $loginUsername, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()> 0) {
                $loginResult = $stmt->fetch();
                $hashedPassword = $loginResult['user_pass'];
                $user_id = $loginResult['id'];
                $user_name = $loginResult['user_name'];
                $role = $loginResult['role'];

                if (password_verify($password, $hashedPassword)) {
                    // Password is correct
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['userId'] = $user_id;
                    $_SESSION['userName'] = $user_name;
                    $_SESSION['role'] = $role;
                    
                    //echo $_SESSION['userId'];
                    //echo $_SESSION['userName'];
                    // Redirect to the home page
                    $message = "login success -> " . $user_id . '_' . $user_name;
                    userActivityLogs($message);
                    redirectToHomePage();
                    exit();
                } 
            }else{
                //invalid user or not registered
                $message = "login error -> '$loginUsername' not registered user";
                userActivityLogs($message);
            }
        } catch (PDOException $e) {
            // Handle database error (log, display a message, etc.)
            //echo "An error occurred while processing your request.";
            //error_log("Database error: " . $e->getMessage());
            return false;
            $message = error_log("Database error: " . $e->getMessage());
            userActivityLogs($message); 
        }
    }
    
    public function logout(){
        session_start();
        $username = $_SESSION['userName'];
        
        date_default_timezone_set('Asia/Manila');
        $timestamp = date('Y-m-d h:i A');
        
        $sql ="UPDATE userActivity SET status = 'Offline', logoutTime = ? WHERE username = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $timestamp, PDO::PARAM_STR);
        $stmt->bindParam(2, $username, PDO::PARAM_STR);
    
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $message = "Logout & update status success -> ". $_SESSION['userId'] 
                . '_' . $_SESSION['userName'];
                userActivityLogs($message);
            }else{
                $message = 'Logout & update status error -> '. $_SESSION['userId'] 
                . '_' . $_SESSION['userName'];
                userActivityLogs($message);
            }
        }else{
             $message = 'Logout & update status execution problem -> '. $_SESSION['userId'] 
                . '_' . $_SESSION['userName'];
                userActivityLogs($message);
        }

        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session
        
        session_destroy();
        
        // Redirect to the login page or any other desired page after logout
        redirectToLoginPage();
        exit();
    }

    public function newUser($user, $email, $password, $role)
    {
        // Check if the username and email already exist in the database
        $checkSql = "SELECT id FROM users WHERE user_name = ? OR user_email = ? ";
        $checkStmt = $this->connect()->prepare($checkSql);
        $checkStmt->bindParam(1, $user, PDO::PARAM_STR);
        $checkStmt->bindParam(2, $email, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // A user with the same username or email already exists
            $result = 'Username or email already exists in the database';
        } else {
            // User doesn't exist, proceed with the insertion
            $sql = "INSERT INTO users (user_name, user_pass, user_email, role) VALUES (?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql); 

            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bindParam(1, $user, PDO::PARAM_STR);
            $stmt->bindParam(2, $hashPassword, PDO::PARAM_STR);
            $stmt->bindParam(3, $email, PDO::PARAM_STR);
            $stmt->bindParam(4, $role, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $result = 'New user record inserted successfully';
            } else {
                $result = 'Please try again!';
            }
        }

        return $result;
    }

    public function deleteUserInfo($user, $email)
    {
        // Check if the record exists in the database
        $sql_check = "SELECT id FROM users WHERE user_name = ? AND user_email = ?";
        $stmt_check = $this->connect()->prepare($sql_check);
        $stmt_check->bindParam(1, $user, PDO::PARAM_STR);
        $stmt_check->bindParam(2, $email, PDO::PARAM_STR);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // The record exists, proceed with deletion
            $sql_delete = "DELETE FROM users WHERE user_name = ? AND user_email = ?";
            $stmt_delete = $this->connect()->prepare($sql_delete);
            $stmt_delete->bindParam(1, $user, PDO::PARAM_STR);
            $stmt_delete->bindParam(2, $email, PDO::PARAM_STR);

            // Execute the deletion query
            if ($stmt_delete->execute()) {
                $result = 'Deletion was successful';
            } else {
                $result = 'Deletion failed';
            }
        } else {
            $result = 'Record does not exist in the database';
        }

        return $result;
    }

    public function editUser($user, $email, $password, $role)
    {
        // Check if the user exists
        $checkSql = "SELECT id FROM users WHERE user_name = ? AND user_email = ?";
        $checkStmt = $this->connect()->prepare($checkSql);
        $checkStmt->bindParam(1, $user, PDO::PARAM_STR);
        $checkStmt->bindParam(2, $email, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // User exists, proceed with the update
            $sql = "UPDATE users SET user_pass = ?, role = ? WHERE user_name = ? AND user_email = ?";
            $stmt = $this->connect()->prepare($sql);

            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bindParam(1, $hashPassword, PDO::PARAM_STR);
            $stmt->bindParam(2, $role, PDO::PARAM_STR);
            $stmt->bindParam(3, $user, PDO::PARAM_STR);
            $stmt->bindParam(4, $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $result = 'User record updated successfully';
            } else {
                $result = 'Update failed';
            }
        } else {
            $result = 'User not found';
        }

        return $result;
    }
}
?>