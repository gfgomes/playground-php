<?php
// Create an indexed array of bestselling book titles
$bestSellers = [
    "PHP 8 Cookbook",
    "The shadow of the wind",
    "The Da Vinci Code",
    "Harry Potter and the Philosopher's Stone",
    "Blame it on the stars"
];

// Prints the first bestselling book
echo "The best selling book is: " . $bestSellers[0] . "<br/>";

// Adds a new book to the end of the list
$bestSellers[] = "1984";

// Prints the updated list of best sellers
for ($i = 0; $i < count($bestSellers); $i++) {
    $position = $i + 1;
    echo "{$position}º: {$bestSellers[$i]} <br/>";
}
echo "<br/>";

// foreach ($array as $chave => $valor) {
//     // Código a ser executado para cada $chave e $valor
// }

foreach ($bestSellers as $index => $book) {
    $position = $index + 1;
    echo "{$position}º: {$book} <br/>";
}

$idades = array("João" => 30, "Maria" => 25);
foreach ($idades as $nome => $idade) {
    echo "{$nome} tem {$idade} anos.<br>"; // Saída: João tem 30 anos., Maria tem 25 anos.
}