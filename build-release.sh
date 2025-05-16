# Ativa o mod_rewrite para redirecionamentos
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redireciona tudo para o index.php (para frameworks como CodeIgniter/Laravel)
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L,QSA]
</IfModule>

# Bloqueia acesso a arquivos/diretórios sensíveis
<FilesMatch "\.(env|log|sql|ini|bak|conf|key)$">
    <IfModule authz_core_module>
        Require all denied
    </IfModule>
    <IfModule !authz_core_module>
        Deny from all
    </IfModule>
</FilesMatch>

# Bloqueia acesso ao diretório /system (se existir)
<Directory "/system">
    <IfModule authz_core_module>
        Require all denied
    </IfModule>
    <IfModule !authz_core_module>
        Deny from all
    </IfModule>
</Directory>

# Impede listagem de diretórios
Options -Indexes

# Redirecionamentos de erro
ErrorDocument 502 "Problemas 502"
ErrorDocument 403 "Acesso negado"
ErrorDocument 404 "Página não encontrada"
