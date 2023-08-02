
<?php
    // Para assegurar que a page não envie quaisquer dados antes que seja permitida...
    ob_start();
?>

<HTML>
<HEAD>
    <TITLE>Sessão e Cookies: Autenticação</TITLE>
</HEAD>
<BODY>
    
    <?php

        /* PASSO 1: Autenticação do usuário */

        // Caso esteja definido o cookie "visitas":
        if(isset($_COOKIE["visitas"])) {
            
            // Add 1 à var ($visitas) a cada vez que acessar a page
            $visitas = $_COOKIE["visitas"] + 1;

        } else {        // Caso não esteja definido o cookie "visitas":

            $visitas = 1;

        }
    
        setcookie("visitas", $visitas, time() + 30*24*60*60); // Definindo o cookie "visitas" | Criação do cookie
        
        echo "Essa é a sua visita número " . $visitas . " em nosso site.<BR>";
        
        // Se o form estiver sendo enviado:
        if(isset($_REQUEST["autenticar"]) && $_REQUEST["autenticar"] == true) {

            $hashDaSenha = md5($_POST["senha"]);    // Para a encriptação da senha (1)
            
            // Conexão com a BD:
            try {

                $connection = new PDO("mysql:host=localhost; dbname=bd_session_cookie", "root", "");
                $connection->exec("set names utf8");

            } catch(PDOException $e) {

                echo "Falha: " . $e->getMessage();
                exit();

            }
            
            $sql = "SELECT nome FROM usuarios WHERE email = ? AND senha = ?"; // Consulta de autenticação
    
            $rs = $connection->prepare($sql);   // resultSet
            $rs->bindParam(1, $_POST["email"]);
            $rs->bindParam(2, $hashDaSenha);    // Para a encriptação da senha (2)
            // Caso o banco não guarde senha encriptada (3) | // $rs->bindParam(2, $_POST["senha"]);
            
            // Se executar com sucesso a consulta ao BD:
            if($rs->execute()) {

                // Se existir o registo esperado:
                if($registo = $rs->fetch(PDO::FETCH_OBJ)) {

                    session_start(); // Inicia a sessão
                    $_SESSION["usuario"] = $registo->nome; // Atribui o campo nome do registo à variável de sessão "usuário"
                    
                    header("location: Sessao_Cookies_ConteudoSigiloso.php"); // Redirecciona para o endereço definido pelo novo cabeçalho
                    
                } else {        // Se não existir o registo esperado:

                    echo "Dados inválidos.";

                }
                
            } else {        // Se não efectuar a consulta com sucesso no BD:

                echo "Falha no acesso.";

            }
            
        }
    
    ?>
    
    <!-- Form (p/ autenticação) -->
    <FORM method=POST action="?autenticar=true">

        E-mail: <INPUT type=TEXT name=email><BR>
        Senha:  <INPUT type=PASSWORD name=senha><BR>

        <INPUT type=SUBMIT value="Autenticar">

    </FORM>
    
</BODY>
</HTML>

<?php
    // Liberado o envio de dados pelo cabeçalho atual
    ob_flush();
?>