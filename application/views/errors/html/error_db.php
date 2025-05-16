<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erro no Banco de Dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 800px;
            padding: 2rem;
        }
        
        .error-code {
            color: #dc3545;
            font-size: 4rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .error-details {
            background: #f8f9fa;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            padding: 1rem;
            white-space: pre-wrap;
        }
        
        .admin-contact {
            border-left: 3px solid #dc3545;
            padding-left: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="error-container mt-5">
            <div class="text-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545" class="bi bi-database-x" viewBox="0 0 16 16">
                    <path d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1-.264.16 15.4 15.4 0 0 1-.564.21l-.003.001-.026.009-.054.02a4.8 4.8 0 0 1-.437.17c-.2.067-.43.132-.692.195C9.9 8.559 9.1 8.68 8 8.68s-1.9-.12-2.657-.277a9 9 0 0 1-.692-.194 4.8 4.8 0 0 1-.49-.19l-.052-.02-.025-.009-.001-.001a3.1 3.1 0 0 1-.264-.158C3.213 7.654 3 7.288 3 7V5.698q.374.217.753.485A5 5 0 0 0 8 6.93a5 5 0 0 0 4.096-.707M14 3.857v3.057q-.308.155-.63.295c-1.33.473-2.992.678-4.37.678s-3.04-.205-4.37-.678a10 10 0 0 1-.63-.295V3.857a5 5 0 0 1 4.253-2.43L8 1.4l.253.027A5 5 0 0 1 14 3.857M6.936 5.28 8 6.344 9.064 5.28l.72.72L8.72 7.064 9.78 8.128l-.72.72L8 7.78 6.936 8.844l-.72-.72L7.28 7.064 6.22 6l.72-.72zM14 5.698v1.302c0 .37-.116.654-.418.895a5 5 0 0 1-2.059.772c.255-.266.477-.557.66-.872.202-.342.363-.72.482-1.126.102-.345.16-.706.173-1.078.012-.315 0-.63-.022-.944a3.7 3.7 0 0 0-.07-.59 5 5 0 0 0 1.054.424z"/>
                </svg>
                <h1 class="error-code mt-3">Erro de Banco de Dados</h1>
            </div>

            <div class="alert alert-danger">
                <h2 class="h4"><?php echo $heading; ?></h2>
                <p class="mb-0"><?php echo $message; ?></p>
            </div>

            <?php if (ENVIRONMENT !== 'production'): ?>
            <div class="mt-4">
                <h3 class="h5">Detalhes Técnicos:</h3>
                <div class="error-details">
                    <?php echo 'Código do Erro: ' . $error_code . "\n"; ?>
                    <?php echo 'Data/Hora: ' . date('d/m/Y H:i:s') . "\n"; ?>
                    <?php echo 'URI: ' . $_SERVER['REQUEST_URI'] . "\n"; ?>
                    <?php echo 'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n"; ?>
                    <?php echo 'SGBD: ' . $dbdriver . "\n"; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="admin-contact mt-4">
                <h3 class="h5">Precisa de ajuda?</h3>
                <p class="mb-0">
                    Contate o administrador do sistema em:
                    <a href="mailto:suporte@empresa.com" class="text-danger">suporte@empresa.com</a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="<?php echo base_url(); ?>" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
                    </svg>
                    Voltar para a Página Inicial
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
