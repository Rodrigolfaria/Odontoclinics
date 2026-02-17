<?php
include '../../conexao.php';

$nome_clinica = $_POST['nome_clinica'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$whatsapp = $_POST['whatsapp'];
$nome_dentista = $_POST['nome_dentista'];
$especialidades = $_POST['especialidades'];
$horario = $_POST['horario'];

$logo_path = null;

// Upload da logo
if (!empty($_FILES['logo']['name'])) {

    $arquivo = $_FILES['logo'];
    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {

        $novo_nome = "logo_clinica." . $ext;
        $destino = "../../assets/images/" . $novo_nome;

        move_uploaded_file($arquivo['tmp_name'], $destino);

        // Caminho para exibir no site
        $logo_path = "http://localhost:8888/odontoclinics/src/assets/images/" . $novo_nome;
    }
}

// Atualizar banco
$sql = "UPDATE configuracao_clinica SET 
    nome_clinica='$nome_clinica',
    endereco='$endereco',
    telefone='$telefone',
    whatsapp='$whatsapp',
    nome_dentista='$nome_dentista',
    especialidades='$especialidades',
    horario='$horario'";

if ($logo_path) {
    $sql .= ", logo='$logo_path'";
}

$sql .= " WHERE id=1";

$conn->query($sql);

header("Location: configuracao-clinica.php?sucesso=1");
exit;
