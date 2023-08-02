
<?php
    // Para assegurar que a page não envie quaisquer dados antes que seja permitida...
    ob_start();
?>

<HTML>
<HEAD>
    <TITLE>Sessão e Cookies: Conteúdo Sigiloso</TITLE>
</HEAD>
<BODY>
    
    <?php
    
        /* PASSO 2: Acesso ao conteúdo sigiloso */

        session_start(); // Início de sessão
    
        // Se não for definida nenhuma sessão "usuario":
        if (!isset($_SESSION["usuario"])) {

            echo "Erro";
            exit();     // Pára a execução do código

        }
        
        // else:
        echo "Olá " . $_SESSION["usuario"];
        echo "<BR><BR>";
    ?>
    
    <!-- Conteúdo do corpo da page -->
    [Conteúdo privado / sigiloso]
    
</BODY>
</HTML>

<?php
    // Liberado o envio de dados pelo cabeçalho atual
    ob_flush();
?>


