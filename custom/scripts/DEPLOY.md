# Empacotamento para deploy

Execute na raiz do projeto:

```powershell
.\custom\scripts\build-deploy.bat
```

O comando cria um arquivo ZIP dentro de `.deploy`, com data e hora no nome.
Esse ZIP pode ser enviado e extraido no servidor de hospedagem.

O pacote exclui:

- arquivos e diretorios temporarios da raiz com os prefixos `tmp_`, `tmp-`,
  `.tmp`, `temp_` ou `temp-`;
- `.git`, `.agents`, `.codex` e `.deploy`;
- `wp-config.php`, para nao sobrescrever as credenciais e configuracoes do
  servidor de producao;
- o proprio arquivo ZIP de destino, caso seja criado dentro do projeto.

Esses prefixos nao sao filtrados dentro de plugins, temas ou outros
subdiretorios, evitando uma remocao ampla demais.

Para escolher outro nome ou destino:

```powershell
.\custom\scripts\build-deploy.bat -OutputPath C:\pacotes\fisica.zip
```

O script apenas monta o pacote. Ele nao altera os arquivos do projeto, nao
exporta o banco de dados e nao envia nada ao servidor.
