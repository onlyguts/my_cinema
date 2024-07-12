<?php 
include_once('../../mysql.php');
$getData = $_POST;


$insertData = $mysqlClient->prepare('UPDATE user SET email =:email, firstname =:firstname, lastname =:lastname, birthdate =:birthdate, address =:address, zipcode =:zipcode, city =:city, country =:country WHERE id = :id');
$insertData->execute([
    'id' => $getData['id'],
    'email' => $getData['email'],
    'firstname' => $getData['firstname'],
    'lastname' => $getData['lastname'],
    'birthdate' => $getData['date'],
    'address' => $getData['address'],
    'zipcode' => $getData['zipcode'],
    'city' => $getData['city'],
    'country' => $getData['country'],
]);