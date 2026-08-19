# Deploy de arquivos e conteudo do banco

Execute na raiz do projeto:

```powershell
.\custom\scripts\build-deploy.bat
```

O comando cria dois arquivos com a mesma data e hora dentro de `.deploy`:

```text
fisica-deploy-AAAAMMDD-HHMMSS.zip
fisica-deploy-AAAAMMDD-HHMMSS-database.sql
```

## Pacote de arquivos

O ZIP pode ser extraido e sincronizado com o servidor pelo WinSCP. Ele exclui:

- arquivos e diretorios temporarios da raiz com os prefixos `tmp_`, `tmp-`,
  `.tmp`, `temp_` ou `temp-`;
- `.git`, `.agents`, `.codex` e `.deploy`;
- `wp-config.php`, preservando credenciais e configuracoes de producao;
- `custom/database_export`, que contem dumps completos e nao deve ser
  publicado;
- os arquivos de saida do proprio deploy.

O ZIP contem o projeto completo. A sincronizacao do WinSCP e que compara os
arquivos e transfere somente os diferentes. Nao habilite a exclusao automatica
de arquivos remotos no primeiro deploy.

## SQL idempotente

O arquivo `*-database.sql` e um deploy seletivo de conteudo. Ele pode ser
executado novamente sem criar duplicatas. O SQL atualiza ou cria:

- paginas;
- anexos e seus metadados;
- dados editaveis do Elementor;
- templates do Elementor;
- cabecalho e rodape do Header Footer Elementor;
- menus e relacionamentos;
- pagina inicial, pagina de posts, kit ativo e estrutura de links permanentes.

O SQL nao remove paginas ou anexos exclusivos da producao e nao altera:

- `siteurl` e `home`;
- usuarios e senhas;
- comentarios;
- plugins ativos;
- e-mail administrativo;
- credenciais, tokens ou configuracoes de conexao;
- sessoes, logs, revisoes ou caches locais.

Conteudos com o mesmo tipo e slug sao controlados pelo projeto local e serao
atualizados. Itens exclusivos da producao sao preservados.

O SQL usa o prefixo de tabelas do ambiente local (`wp_`). O `wp-config.php` de
producao precisa utilizar o mesmo prefixo.

## Procedimento recomendado em producao

1. Faca backup completo dos arquivos e do banco de producao.
2. Extraia o ZIP localmente.
3. Sincronize os arquivos pelo WinSCP, no sentido local para remoto.
4. Revise a lista e mantenha desmarcada a exclusao de arquivos remotos.
5. Importe o arquivo `*-database.sql` no banco correto pelo phpMyAdmin ou pelo
   cliente MariaDB/MySQL.
6. No WordPress, acesse `Elementor > Ferramentas` e execute a regeneracao dos
   arquivos CSS e dados.
7. Limpe o cache do servidor e do navegador.
8. Verifique a pagina inicial, menus, paginas internas, imagens e painel.

Nunca use `custom/database_export/fisica.sql` para esse procedimento. Esse e
um dump integral de backup, nao o deploy idempotente.

## Opcoes do build

Escolher o destino do ZIP:

```powershell
.\custom\scripts\build-deploy.bat -OutputPath C:\pacotes\fisica.zip
```

Escolher tambem o destino do SQL:

```powershell
.\custom\scripts\build-deploy.bat `
  -OutputPath C:\pacotes\fisica.zip `
  -DatabaseOutputPath C:\pacotes\fisica-database.sql
```

Gerar para outra URL de destino:

```powershell
.\custom\scripts\build-deploy.bat -TargetUrl https://homologacao.exemplo.br
```

Gerar somente o ZIP, sem banco:

```powershell
.\custom\scripts\build-deploy.bat -SkipDatabase
```
