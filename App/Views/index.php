<?php

session_start();
    
if ((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)){
    header('Location: menu.php');
    exit();
}

if(isset($_POST['login'])){
	//Flagg
    $validation_OK = true;

    require_once "connect.php"; 

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    mysqli_query($connection, "SET CHARSET utf8");
    mysqli_query($connection, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

	if($connection->connect_errno!=0){
		echo "Error: ".$connection->connect_errno;
	}
    else{
        $login = $_POST['login'];
        $password = $_POST['password'];
                
        if ($result = @$connection->query(
            sprintf("SELECT * FROM clients WHERE userLogin='%s'",
            mysqli_real_escape_string($connection,$login)))){

            $user_number = $result->num_rows;

            if($user_number > 0){
                $line = $result->fetch_assoc();

                if(password_verify($password,$line['userPassword'])){
                    $_SESSION['userLogin'] = $line['userLogin'];
                    $_SESSION['userId'] = $line['userId'];
                    $_SESSION['logged'] = true;
                    unset($_SESSION['e_error']);
                    header('Location: menu.php');
                }
                else{
                    $_SESSION['e_error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                }
            }
            else{
                $_SESSION['e_error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            }
        }
        $connection->close();     
    }
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Logowanie | TB twojbudzet.com</title>
    <meta name="description" content="Strona, na której możesz stworzyć swój domowy budżet" />
    <meta name="keywords" content="budżet, domowy, oszczędności, plany" />
    <meta name="author" content="Małgorzata Mickiewicz">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/fontello.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display&display=swap" rel="stylesheet" />

    <!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->

</head>

<body>

    <header>

        <nav class="navbar bg-budget navbar-dark navbar-expand-lg text-center">
            <a class="navbar-brand logo ml-2" href="#">Your Budget</a>

        </nav>

    </header>

    <main>
        <div class="container">

            <div class="row text-center bg-background my-4 p-sm-3 p-lg-0">

                <div class="col-lg-4 offset-lg-1 bg-white my-4 shadow p-3">

                    <h1 class="h3 font-weight-bold my-4">Logowanie</h1>
                    <form method="post">

                        <div class="col-10 offset-md-1 input-group mb-4">

                            <div class="input-group-prepend">
                                <span class="input-group-text login-color"> ✉ </span>

                            </div>

                            <input type="text" name="login" class="form-control" placeholder="*Login" id="login"
                                aria-label="login" aria-describedby="login">
                        </div>

                        <div class="col-10 offset-md-1 input-group mb-4">

                            <div class="input-group-prepend">

                                <span class="input-group-text login-color">
                                    <svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="11" height="9" x="2.5" y="7" rx="2" />
                                        <path fill-rule="evenodd"
                                            d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>

                            </div>

                            <input type="password" name="password" class="form-control" placeholder="*Hasło" id="haslo"
                                aria-label="haslo" aria-describedby="haslo">

                        </div>

                        <button type="submit" class="btn-login my-3">Zaloguj się</button>
                        <p>*Pole wymagane</p>
                    </form>

                    <?php
                        if(isset($_SESSION['e_error'])) {
                            echo '<div style="color: red;">'.$_SESSION['e_error'].'</div>';
                            unset($_SESSION['e_error']);
                        }
                    ?>

                </div>

                <div class="col-lg-4 offset-lg-2 offset-xs-0 bg-white my-4 shadow p-3">
                    <h1 class="h3 font-weight-bold my-4">Nie masz jeszcze konta? <br />Zarejestruj się!</h1>
                    <a href="register.php">
                        <button type="submit" class="btn btn-register my-3">Rejestracja </button>
                    </a>
                    <p class="h4 font-weight-bold mb-2 mt-2"> Dlaczego warto założyć konto? </p>

                    <p class="mt-4 mb-0"> ★ Zyskujesz dostęp do historii swojego budżetu </p>
                    <p>★ Nie widzisz reklam </p>

                </div>
            </div>
        </div>

    </main>

    <footer class="fixed-bottom">    
        <div class="footer">
         © Małgorzata Mickiewicz <a href="malgorzatamickiewicz.pl/kontakt">contact with me</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>


</body>

</html>