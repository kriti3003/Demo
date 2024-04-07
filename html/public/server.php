<?php 
session_start();

// variables 
$email = "";
$errors = array();

// connect to database 
$db = mysqli_connect('localhost', 'root', '', 'project');

// login user 
if(isset($_POST['login_user'])){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(empty($email)) { array_push($errors, "Email is required");}
    if(empty($password)) { array_push($errors, "Password is required");}

    if(count($errors) == 0){
        $password = md5($password); 
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' ";
        $results = mysqli_query ($db, $query);
        if(mysqli_num_rows($results) == 1){
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are logged in";
            header('location: index.html');
        } else{
            array_push($errors, "Wrong username or password");
        }
    }
}

// register user 
if(isset($_POST['reg_user'])){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form is filled correctly 
    if(empty($email)) { array_push($errors, "Email is required");}
    if(empty($password_1)) { array_push($errors, "Password is required");}
    if($password_1 != $password_2){
        array_push($errors, "Two passwords don't match");
    }

    //checking if user already exists 
    $user_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if($user) {
        if($user['email'] == $email){
            array_push($errors, "email already exists"); 
        }
    }
    if(count($errors) == 0){
        $password = md5($password_1); 
        $query = "INSERT INTO users (email, password) VALUES('$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['email'] = $email;
        $_SESSION['success'] = "You are logged in";
        header('location: index.html');
    }
}
?>
