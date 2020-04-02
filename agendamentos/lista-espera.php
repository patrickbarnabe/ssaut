<?php
session_start();
include_once("../db/conexao.php");
//echo " ". $_SESSION['id-evento'] ."<BR>";
//echo " ". $_SESSION['turma'] ."<BR>";

$id = $_SESSION['id-evento'];
//$escola = $_SESSION['usuarioId'];
$escola = $_SESSION['escola'];
$turma = $_SESSION['turma'];
$QTD_alunos = $_SESSION['QTD_alunos'];
$vagas = $_SESSION['vagas'];

if($QTD_alunos > $vagas ) // SE AS VAGAS NÃO FOREM SUFICIENTES, OU SEJA, SE A QUANTIDADE DE ALUNOS FOREM MAIOR DO QUE A QUANTIDADE DE VAGAS
{
	// se QTD_VAGAS = 0, então o pedido é para entrar na lista de espera, caso contrário, então o pedido é de abertura de mais vagas

	//$query = "SELECT COUNT(id) AS ja_fez FROM lista_espera WHERE (evento='$id' AND escola='$escola' AND turma='$turma' AND qtd_vagas = 0)";
	//$query = "SELECT COUNT(id) AS ja_fez FROM pedidos WHERE (evento='$id' AND escola='$escola' AND turma='$turma' AND qtd_vagas = 0)";
	$query = "SELECT COUNT(id) AS ja_fez FROM lista_espera WHERE (evento='$id' AND escola='$escola' AND turma='$turma')";
	$result_query = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result_query);
	$ja_fez = $row['ja_fez'];

	if($ja_fez == 0)
	{
		//$result_events = "INSERT INTO pedidos (evento, escola, turma, qtd_vagas) VALUES ('$id', '$escola', '$turma',  0)";
		$result_events = "INSERT INTO lista_espera (evento, escola, turma, qtd_alunos_turma, avisado, confirmado, excluido) VALUES ('$id', '$escola', '$turma',  '$QTD_alunos', 0, 0, 0)";	
		$resultado_events = mysqli_query($conn, $result_events);	

		if($_SESSION['permissao'] == 3) // SE USUÁRIO FOR ESCOLA
		{
			$_SESSION['msg'] = "<div class='alert alert-success' role='alert'> Você foi adicionado na Lista de Espera deste Evento! Em caso de desistencia para os agendamentos dele, você será informado. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
			header("Location: t-c-agendamentos.php");
		}
		else // SE USUÁRIO FOR FUNCIONÁRIO
		{				
			$_SESSION['funcionario_agendou'] = true;
			header("Location: t-c-agendamentos.php");
		}
	}
	else
	{
		$_SESSION['msg'] = "<div class='alert alert-warning' role='alert'> Você já está na Lista de Espera deste Evento <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		header("Location: t-c-agendamentos.php");
	}
}
else
{
	$_SESSION['msg'] = "<div class='alert alert-warning' role='alert'> Este Evento ainda tem vagas suficientes para a quantidade de alunos da sua turma. Por isto você NÃO foi adicionado a lista de espera. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
	header("Location: t-c-agendamentos.php");
}

?>