# webhook_test.ps1

# Testar status cancelado
$json1 = '{ "pedido_id": 1, "status": "cancelado" }'
$response1 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json1 -ContentType 'application/json'
Write-Host "Resposta cancelado:" $response1

# Testar status entregue
$json2 = '{ "pedido_id": 2, "status": "entregue" }'
$response2 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json2 -ContentType 'application/json'
Write-Host "Resposta entregue:" $response2

# Testar status enviado
$json3 = '{ "pedido_id": 3, "status": "enviado" }'
$response3 = Invoke-RestMethod -Uri http://localhost/mlbaunilha-erp/webhook/atualizar -Method POST -Body $json3 -ContentType 'application/json'
Write-Host "Resposta enviado:" $response3
