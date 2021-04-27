<?php
// ============================== Exercicio 1 =======================================
function validateEmail($email) 
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email inválido!");
    }
}

function validatePhone($phone) 
{
    $onlyNumbers = preg_replace("/[^0-9]/", "", $phone);

    $numbersBlocks = array (
        '0000000000', '00000000000',
        '1111111111', '11111111111',
        '2222222222', '22222222222',
        '3333333333', '33333333333',
        '4444444444', '44444444444',
        '5555555555', '55555555555',
        '6666666666', '66666666666',
        '7777777777', '77777777777',
        '8888888888', '88888888888',
        '9999999999', '99999999999'
    );

    if (strlen($onlyNumbers) < 10 || strlen($onlyNumbers) > 11) {
        throw new Exception('Celular inválido!');
    } else if (in_array($onlyNumbers, $numbersBlocks)) {
        throw new Exception('Celular inválido!');
    }
}

function validateCEP($cep) 
{
    $onlyNumbers = preg_replace("/[^0-9]/", "", $cep);

    if(strlen($onlyNumbers) != 8) {
        throw new Exception("CEP inválido!"); 
    } 
}

function validatePassword($password)
{
    if ( strlen($password) < 8 ) {
        throw new Exception("A senha deve ter no mínimo 8 caractéres.");

    } else if ( !preg_match("/[a-zA-Z]/", $password) ) {
        throw new Exception("A senha deve ter pelo menos 1 letra.");

    } else if ( !preg_match("/[0-9]/", $password) ) {
        throw new Exception("A senha deve ter pelo menos 1 número.");

    }
}

function select($informacao)
{
    $connection = mysqli_connect('HOST', 'USUARIO', 'SENHA', 'DB');

    try {
        validateEmail($informacao['email']);
        validatePhone($informacao['phoneNumber']);

        $query = "SELECT * FROM Tabela 
                    WHERE email = ". $informacao['email'] . 
                    "AND phone = " . $informacao['phoneNumber'];

        if(mysqli_query($connection, $query)) {
            return true;
        }
    } catch (\Throwable $error) {
        return false;
    }
}

function insert($informacao) 
{
    $connection = mysqli_connect('HOST', 'USUARIO', 'SENHA', 'DB');

    try {
        $name = filter_var($informacao['userName'], FILTER_SANITIZE_STRING);
        validateEmail($informacao['email']);
        validatePhone($informacao['phoneNumber']);
        validatePassword($informacao['password']);
        validateCEP($informacao['zipCode']);

        $query = "INSERT INTO Tabela (nome, cep, phone, email, senha)  
                    VALUES (". $name . ",". $informacao['zipCode'].",". $informacao['phoneNumber'].",". $informacao['email'].",". $informacao['password'].")";

        if(mysqli_query($connection, $query)) {
            return true;
        }
    } catch (\Throwable $error) {
        return false;
    }
}

function curl($url, $informacao) 
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($informacao),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Content-Type: application/json",
        ),
    ));

    $response = json_decode(curl_exec($curl));
    
    $error = curl_error($curl);

    curl_close($curl);

    if ($error) {
        throw new Exception('Erro ao fazer requisição.');
        exit;
    }

    return $response;
}

// ============================== Exercicio 2 =======================================

// 1) Crie um array
$numbersArray = [];

// 2) Popule este array com 7 números
$numbersArray = [1, 4, 8, 14, 34, 28, 22];

// 3) Imprima o número da posição 3 do array
echo $numbersArray[2];

// 4) Crie uma variável com todas as posições do array no formato de string separado por vírgula
$numbersString = "";

$i = 0;
foreach ($numbersArray as $number) {
    if( $i != 0 ) {
        $numbersString = $numbersString . ", " . $number;
    } else {
        $numbersString = $number;
    }

    $i++;
}

// 5) Crie um novo array a partir da variável no formato de string que foi criada
$newNumbersArray = explode(",", $numbersString);


// 6) Crie uma condição para verificar se existe o valor 14 no array
foreach($numbersArray as $number) {
    if ($number == 14) {
        echo "Existe o número 14";
    }
}

// Faça uma busca em cada posição. Se o número da posição atual for menor que a posição anterior, exclua esta posição
$i = 0;
$lastPosition = 0;

foreach($numbersArray as $number) {
    if($number < $lastPosition) {
        unset($numbersArray[$i]);
    } 

    $lastPosition = $number;
    $i++;
}

// 8) Remova a última posição deste array
$removeLastValue = array_pop($numbersArray);

// 9) Conte quantos elementos tem neste array
$countElements = count($numbersArray);

// 10) Inverta as posições deste array
$alterOrderArray = array_reverse($numbersArray);

// ============================== Exercicio 3 =======================================

/*
Cria a tabela Usuário
CREATE TABLE IF NOT EXISTS `Usuario` (
    `id` bigint NOT NULL,
    `cpf` varchar(11) NOT NULL, 
    `nome` varchar(200) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;
Cria a tabela Info
CREATE TABLE IF NOT EXISTS `Info` (
`id` int(6) NOT NULL,
`cpf` varchar(11)  NOT NULL,  
`genero` varchar(1)  NOT NULL,
`ano_nascimento` int(4) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

Preenche a tabela Usuario
INSERT INTO `Usuario` (`id`, `cpf`, `nome`) VALUES
('1', '16798125050', 'Luke Skywalker'),
('2', '07583509025', 'Bruce Wayne'),
('3', '04707649025', 'Diane Prince'),
('4', '21142450040', 'Bruce Banner'),  
('5', '83257946074', 'Harley Quinn'),
('6', '59875804045', 'Peter Parker'); 

Preenche a tabela Info
INSERT INTO `Info` (`id`, `cpf`, `genero`, `ano_nascimento`) VALUES
('1', '16798125050', 'M', '1976'),
('2', '07583509025', 'M', '1960'),
('3', '04707649025', 'F', '1988'),
('4', '21142450040', 'M', '1954'),
('5', '83257946074', 'F', '1970'),
('6', '59875804045', 'M', '1972');   
*/

$query =   'SELECT 
                CONCAT(u.nome, " - ", i.genero) AS usuario,
                IF( YEAR(CURDATE()) - ano_nascimento > 50, "SIM", "NÃO") AS maior_50_anos 
            FROM Usuario u 
            INNER JOIN Info i 
            ON u.id = i.id';
