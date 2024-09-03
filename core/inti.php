<?php
    use App\Database;
    use App\User;
    use App\Conversations;

    require __DIR__ . "/../vendor/autoload.php";
    session_start(); 

    if(!User::isLoggedIn()){
        header('Location: login.php');
    }

    $token = $_SESSION['user']['token'];

    $database = new Database();
    $allActivities = new Conversations($database);
    $onlineUsers = $allActivities->getOnlineUsers();

    // $assets = new AssetService();
    // $asset->type()->register();

