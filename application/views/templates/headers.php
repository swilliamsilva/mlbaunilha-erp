// application/views/templates/header.php
?><!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>ERP MLBaunilha</title>
  <style> body { background-color: #ffe600; } footer { border-top: 2px solid black; } </style>
</head>
<body>
<div class="d-flex">
  <nav class="bg-dark text-white p-3" style="width: 200px; height: 100vh;">
    <h5>Menu</h5>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link text-white" href="/clientes">Clientes</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="/produtos">Produtos</a></li>
    </ul>
  </nav>
  <main class="flex-grow-1 p-4">
<?php
