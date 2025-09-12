# 📚 Projeto Física UERJ

Este repositório contém os arquivos do **projeto de Física da UERJ**, desenvolvido em **WordPress** e integrado com um banco de dados **MySQL/MariaDB**.
O objetivo é manter controle de versão do site e do banco de dados associado, garantindo **backup** e **rastreabilidade das alterações**.

---

## 📂 Estrutura do Repositório

* `wp-content/` → Plugins, temas e uploads do WordPress
* `custom/database_export/fisica.sql` → Exportação do banco de dados (estrutura + dados)
* `custom/scripts/export-db.bat` → Script para gerar a exportação do banco
* `.gitignore` → Arquivos ignorados no versionamento (cache, logs, uploads temporários etc.)

---

## ⚙️ Como usar

### 1. Clonar o projeto

```bash
git clone https://github.com/LocDog1978/fisica.git
cd fisica
```

### 2. Configurar o ambiente

* Instale **XAMPP** ou similar.
* Configure o `wp-config.php` com as credenciais do banco.
* Importe o banco de dados (veja abaixo).

### 3. Importar o banco de dados

No **phpMyAdmin** ou via terminal MySQL:

```bash
mysql -u root -p fisica < custom/database_export/fisica.sql
```

### 4. Exportar o banco de dados

Para gerar o arquivo `custom/database_export/fisica.sql`, abra o **PowerShell** na raiz do projeto e execute:

```powershell
.\custom\scripts\export-db.bat
```

📌 Clique no botão **copiar** no canto superior direito do bloco acima para copiar o comando e colar no PowerShell.

---

## 👥 Equipe

* **UERJ - Universidade do Estado do Rio de Janeiro**
* Projeto de apoio ao curso de **Física**

---

## 📜 Licença

Este projeto é de uso interno da **UERJ**.
