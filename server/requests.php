<?php
session_start();
include("../common/db.php");
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO `users` (`id`, `username`, `email`, `password`, `address`) VALUES (NULL, ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $address);

    if ($stmt->execute()) {
        $_SESSION["user"] = ["username" => $username, "email" => $email, "user_id" => $stmt->insert_id];
        header("Location: /discuss"); 
    } else {
        echo "Error: " . $stmt->error; // More specific error message
    }
    $stmt->close();


} else if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = "";
    $user_id = 0;

    $query = "select * from users where email='$email' and password='$password'";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {

        foreach ($result as $row) {

            $username = $row['username'];
            $user_id = $row['id'];
        }

        $_SESSION["user"] = ["username" => $username, "email" => $email, "user_id" => $user_id];
        header("location: /discuss");
    } else {
        echo "New user not registered";
    }

} else if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    header("location: /discuss");
} else if (isset($_POST["ask"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $user_id = $_SESSION['user']['user_id'];

    $question = $conn->prepare("Insert into `questions`
(`id`,`title`,`description`,`category_id`,`user_id`)
values(NULL,'$title','$description','$category_id','$user_id');
");

    $result = $question->execute();
    $question->insert_id;
    if ($result) {
        header("location: /discuss");
    } else {
        echo "Question is added to website";
    }

}elseif (isset($_POST["answer"])) {
    $answer = $_POST['answer'];
    $question_id = $_POST['question_id'];
    $user_id = $_SESSION['user']['user_id'];

    $stmt = $conn->prepare("INSERT INTO `answers` (`id`, `answer`, `question_id`, `user_id`) VALUES (NULL, ?, ?, ?)");
    $stmt->bind_param("sii", $answer, $question_id, $user_id);

    if ($stmt->execute()) {
        header("Location: /discuss?q-id=$question_id"); 
    } else {
        echo "Error: " . $stmt->error; 
    }
    $stmt->close();
}

else if (isset($_GET["delete"])) {
    echo $qid= $_GET["delete"];
     $query= $conn->prepare("delete from questions where id =$qid");
     $result = $query->execute();
     if($result){
        header("location: /discuss");
     }else {
        echo "Question not deleted";
     }
}
?>