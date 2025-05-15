# Corrigir script PowerShell para rodar no ambiente local corretamente (sem trechos de Python)

script_ps1_fixed = """
# webhook_teste_completo.ps1
# 🔹 Executa testes completos: popula banco + chama webhook
# ❗ Execute este script SOMENTE NO POWERSHELL (não no MySQL/phpMyAdmin)

# Caminho do MySQL.exe no XAMPP
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe"
$db = "DBMLBaunilha"
$user = "root"

# 🔸 Etapa 1: Popular a base de dados (SQL)
Write-Host " Populando banco com webhook_test.sql..."
$cmd = "/c `"$mysqlExe -u $user $db < webhook_test.sql`""
Start-Process cmd.exe -ArgumentList $cmd -Wait
Write-Host "- Banco populado."

# Etapa 2: Executar chamadas ao Webhook
$json1 = '{ "pedido_id": 1, "status": "entregue" }'
$response1 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json1 -ContentType 'application/json'
Write-Host "` - Pedido 1 - Entregue: $($response1)"

$json2 = '{ "pedido_id": 2, "status": "cancelado" }'
$response2 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json2 -ContentType 'application/json'
Write-Host "` - Pedido 2 - Cancelado: $($response2)"

$json3 = '{ "pedido_id": 3, "status": "enviado" }'
$response3 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json3 -ContentType 'application/json'
Write-Host "` - Pedido 3 - Enviado: $($response3)"

Write-Host "`- Teste de webhook finalizado."
"""

