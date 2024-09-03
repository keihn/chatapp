<?php

use App\Auth;
use App\Database;

require __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . "/layout/header.php";


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $database = new Database();
    $user = new Auth($database);
    $request = (object) $_POST;
    if ($user->register($request->username, $request->password)) {
        header('Location: ', 200);
    }
}
?>

<section>
    <div class="d-flex justify-content-center align-items-center flex-column" style="height: 100vh;">

        <div class="col-md-4">
            <h1 class="mb-4">Create an account</h1>
        </div>
        <div class="col-md-4">

            <form class="border p-4 bg-light rounded" action="" method="post">
                <div class="mb-3">
                    <input class="form-control" type="text" name="username" placeholder="Username">
                </div>
                <div class="mb-3">
                    <input class="form-control" type="password" name="password" id="" placeholder="Password">
                </div>

                <button class="btn btn-primary" type="submit">Register</button>

                <div class="mb-3 mt-3">
                   <p>Already have an account? <a href="login.php">Log into your account</a></p>
                </div>
            </form>
        </div>
    </div>
</section>