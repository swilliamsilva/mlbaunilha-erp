<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP MLBaunilha</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Estilos customizados -->
    <style>
        body { 
            background-color: #f8f9fa; /* Cor mais suave */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar {
            width: 200px;
            background-color: #2c3e50; /* Azul mais profissional */
            height: 100vh;
            position: fixed;
        }
        
        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
        }
        
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: #34495e !important;
            padding-left: 25px !important;
        }
        
        footer {
            border-top: 2px solid #dee2e6;
            margin-top: auto;
            padding: 1rem;
            background: white;
        }
				
				footer {
					background-color: #2c3e50;
					border-top: 1px solid #34495e;
					margin-top: auto;
			}
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar text-white p-3">
            <div class="d-flex flex-column h-100">
                <h4 class="mb-4">MLBaunilha</h4>
                
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white d-flex align-items-center" href="<?= site_url('clientes') ?>">
                            <i class="bi bi-people me-2"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white d-flex align-items-center" href="<?= site_url('produtos') ?>">
                            <i class="bi bi-box-seam me-2"></i>
                            Produtos
                        </a>
                    </li>
                    <!-- Adicione mais itens do menu -->
                </ul>
                
                <!-- Footer da sidebar -->
                <div class="mt-auto small">
                    <div class="text-muted">v1.0.0</div>
                    <div class="text-muted">© 2023 MLBaunilha</div>
                </div>
            </div>
        </nav>

        <!-- Conteúdo principal -->
        <main class="main-content">
            <!-- Espaço para mensagens flash -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
