# URL do site em producao

A URL canonica do projeto e:

```text
https://fisica.uerj.br
```

Ela fica centralizada na constante `FISICA_SITE_URL`, definida no
`wp-config.php`. O codigo PHP proprio deve montar links com:

```php
fisica_site_url( '/index.php/caminho/' )
```

## Configuracao do wp-config.php de producao

Como o deploy pelo WinSCP protege o `wp-config.php`, copie manualmente o bloco
de configuracao de URL do arquivo local para o `wp-config.php` do servidor. O
bloco define `FISICA_SITE_URL`, `WP_HOME`, `WP_SITEURL` e `WP_CONTENT_URL`.

## Migracao do conteudo do Elementor

A constante nao regrava URLs antigas ja armazenadas no banco. Depois de fazer
backup do banco, no painel de producao acesse:

1. Elementor > Editor > Ferramentas > Substituir URL.
2. URL antiga: `http://localhost/fisica`
3. URL nova: `https://fisica.uerj.br`
4. Execute a substituicao.
5. Em Ferramentas, use Limpar arquivos e dados (ou Regenerar CSS e dados,
   conforme o nome exibido na versao instalada).
6. Limpe o cache do servidor e do navegador.

Nao faca uma substituicao textual direta no arquivo SQL: o banco contem dados
serializados, que podem ser corrompidos por uma troca simples de texto.
