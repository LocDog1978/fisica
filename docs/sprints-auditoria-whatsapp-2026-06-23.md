# Auditoria e Planejamento em Sprints

Data da auditoria: 2026-06-23
Escopo: pedidos recebidos via WhatsApp em 2026-06-18
Status desta etapa: Sprint 2 implementada e validada em 2026-06-23

## Resumo executivo

- [x] Item 1 parcialmente confirmado como feito: HEP com 2 fotos novas; Fisica Moderna com 1 foto nova
- [ ] Item 2 pendente ou no minimo nao confirmado como feito
- [x] Item 3 concluido: links preservados e frase inicial removida
- [x] Item 4 confirmado como feito: frase publicada na abertura da pagina
- [x] Item 5 aparentemente feito: link do Lattes do Gilson ja foi trocado
- [ ] Item 6 pendente: paginas de oportunidades ainda exibem textos genericos e cards placeholder
- [ ] Item 7 parcialmente feito: departamentos foram reescritos, mas nao exibem numero de docentes nem link para lista
- [ ] Item 8 pendente: quadro inicial de Docentes ainda tem informacoes genericas

## Sprint 1

Objetivo: finalizar itens com baixo risco, alta clareza e validacao objetiva.

### 1. Fotos dos laboratorios

Pedido:
- Falta colocar 2 no HEP
- Falta colocar 1 no Fisica Moderna
- Olhar as do Lieta

Status atual:
- [x] HEP aparenta concluido
- [x] Fisica Moderna aparenta concluido

Evidencia:
- `fotos-hepgrid` renderiza as imagens `GEO_5476-2.jpg` e `GEO_6491-2.jpg`
- `fotos-lfm` renderiza ao menos uma imagem nova de `2026-06`, `GEO_3920-2`

Acao futura:
- Revalidar somente se o chefe quiser confirmar se as fotos corretas foram escolhidas a partir do LIETA

### 5. Trocar o link do Lattes do Gilson

Status atual:
- [x] Aparentemente concluido

Evidencia:
- Na pagina `Corpo Docente`, Gilson Correia Silva aponta para `https://buscatextual.cnpq.br/buscatextual/visualizacv.do?metodo=apresentar&id=K4474278P7`

Acao futura:
- Validar apenas se houver um URL alvo especifico diferente

## Sprint 2

Objetivo: fechar ajustes editoriais pontuais com impacto localizado.

### 3. Graduacao

Pedido:
- Retirar a frase "Apresentacao..."
- Colocar os links do fluxograma e ementas

Status atual:
- [x] Remocao da frase inicial concluida em 2026-06-23
- [x] Links de Fluxograma e Ementas ja estao no ar

Evidencia:
- A pagina nao renderiza mais o texto inicial: `Apresentacao institucional do curso de Fisica...`
- Os botoes ja apontam para:
- `https://www.dep.uerj.br/fluxos/fisica_bacharelado.pdf`
- `https://www.dep.uerj.br/fluxos/fisica_licenciatura.pdf`
- `https://www.ementario.uerj.br`

Validacao:
- O hero continua exibindo `Instituto de Fisica UERJ` e `Graduacao`
- A secao interna `Apresentacao` foi preservada
- Os dois links de Fluxograma e os dois links de Ementas foram preservados

### 4. Extensao

Pedido:
- Colocar a frase `Projetos de extensao do Instituto de Fisica`

Status atual:
- [x] Frase confirmada na renderizacao atual em 2026-06-23

Evidencia:
- A pagina carrega o shortcode `[extensao_projetos_excel]`
- O hero renderiza `Projetos de Extensao do Instituto de Fisica`
- Os cards e modais dos projetos continuam publicados

Validacao:
- Nenhuma alteracao adicional foi necessaria no shortcode ou na grade de projetos

## Sprint 3

Objetivo: corrigir estruturas que hoje ainda estao com texto placeholder ou layout provisoriamente generico.

### 6. Bolsas de iniciacao e outras oportunidades

Pedido:
- Deixar as bolsas de iniciacao e as outras arrumadas
- Remover frases e quadradinhos sem oportunidades

Status atual:
- [ ] Pendente

Evidencia:
- `Iniciacao Cientifica`, `Monitorias` e `Estagios` ainda exibem blocos genericos como `Visao geral`, `Destaques`, `Apresentacao` e textos placeholder
- Ainda existe sidebar com textos de navegacao e observacao genericos

Acao futura:
- Definir se o pedido e:
- limpar completamente os placeholders
- manter apenas link de retorno
- ou substituir por conteudo institucional definitivo

Observacao:
- Este item precisa de criterio editorial antes da execucao para nao remover estrutura util e deixar paginas vazias demais

### 8. Docentes

Pedido:
- Retirar as informacoes genericas do quadro inicial de Docentes

Status atual:
- [ ] Pendente

Evidencia:
- A pagina ainda mostra texto generico no topo, como:
- `Use as abas para navegar pelas areas...`
- bloco com total `74`
- cards de resumo com linguagem padrao de interface

Acao futura:
- Decidir se:
- remove apenas o card inicial de apresentacao
- remove todos os cards-resumo
- ou substitui por texto institucional definitivo

## Sprint 4

Objetivo: atuar nos itens com maior ambiguidade sem quebrar a navegacao existente.

### 2. Pesquisa e Desenvolvimento

Pedido:
- Retirar tudo o que esta na faixa da direita exceto o link para voltar

Status atual:
- [ ] Nao confirmado como feito

Leitura tecnica:
- As paginas de linhas de pesquisa foram montadas com o layout `fisica-detail-page`
- Esse layout usa sidebar com multiplos cards
- O pedido parece se referir exatamente a essa coluna lateral

Risco de interpretacao:
- Pode ser um pedido para uma unica pagina
- Pode ser um pedido para todas as paginas de `Pesquisa e Desenvolvimento`
- Pode ser um ajuste do template compartilhado das linhas de pesquisa

Acao futura:
- Identificar primeiro se o alvo e uma pagina especifica ou todas as paginas dessa area
- Depois remover tudo da direita e manter apenas o CTA de voltar

### 7. Departamentos

Pedido:
- Atualizar departamentos com o arquivo enviado em 29 de abril
- Colocar o numero de docentes e o link para a lista de cada um

Status atual:
- [ ] Parcialmente feito

Evidencia:
- As quatro paginas de departamentos ja foram reescritas com textos institucionais
- Hoje elas ainda exibem sidebar generica com `Vinculacao institucional` e `Compromisso com a UERJ`
- Nao encontrei numero de docentes nem link para lista especifica de docentes em cada departamento

Dependencia:
- O arquivo enviado em 29 de abril nao esta explicitamente identificado nesta auditoria

Acao futura:
- Localizar o arquivo-base correto
- Mapear docentes por departamento
- Inserir contagem confiavel
- Inserir link da lista correspondente por departamento

## Itens que pedem confirmacao antes de implementar

### Pesquisa e Desenvolvimento

Pergunta tecnica:
- vale para uma pagina especifica ou para todas as paginas de linhas de pesquisa?

### Bolsas e oportunidades

Pergunta tecnica:
- o objetivo e remover os placeholders e deixar paginas mais secas, ou substituir por conteudo definitivo?

### Departamentos

Pergunta tecnica:
- qual e exatamente o arquivo recebido em 29 de abril e onde ele esta no projeto ou fora dele?

## Matriz de viabilidade, risco, mitigacao e confianca

### Item 1. Fotos dos laboratorios

- Viabilidade: 98%
- Risco: 8%
- Mitigacao: validar apenas correspondencia editorial das imagens com o que o chefe esperava
- Confianca da auditoria: 95%

Motivo:
- A publicacao atual mostra sinais concretos de conclusao e o risco maior e editorial, nao tecnico

### Item 2. Pesquisa e Desenvolvimento

- Viabilidade: 92%
- Risco: 52%
- Mitigacao: definir escopo exato antes, aplicar em uma pagina-modelo e validar se o layout e compartilhado
- Confianca da auditoria: 63%

Motivo:
- A remocao em si e simples, mas o alvo exato ainda esta ambiguo

### Item 3. Graduacao

- Viabilidade: 99%
- Risco: 12%
- Mitigacao: remover somente o paragrafo inicial e revalidar os links existentes
- Confianca da auditoria: 97%

Motivo:
- O problema esta bem localizado e a estrutura da pagina ja esta identificada

### Item 4. Extensao

- Viabilidade: 97%
- Risco: 18%
- Mitigacao: inserir a frase sem mexer na grade ou no shortcode dos projetos
- Confianca da auditoria: 90%

Motivo:
- O ajuste e pequeno, mas o ponto ideal de insercao ainda depende do render atual do shortcode

### Item 5. Lattes do Gilson

- Viabilidade: 100%
- Risco: 5%
- Mitigacao: apenas reconfirmar o URL alvo se houver duvida
- Confianca da auditoria: 96%

Motivo:
- Ha evidencia forte de que o link ja foi trocado

### Item 6. Bolsas de iniciacao e outras

- Viabilidade: 90%
- Risco: 58%
- Mitigacao: alinhar criterio editorial antes, remover placeholder por etapas e validar pagina a pagina
- Confianca da auditoria: 91%

Motivo:
- E tecnicamente simples, mas o risco de interpretar errado o que deve permanecer e alto

### Item 7. Departamentos

- Viabilidade: 84%
- Risco: 66%
- Mitigacao: localizar o arquivo de 29 de abril, definir fonte oficial da contagem e mapear vinculo docente-departamento antes de editar
- Confianca da auditoria: 88%

Motivo:
- Ja houve alteracoes estruturais, mas o pedido atual exige fonte externa e regra de negocio para contagem/lista

### Item 8. Docentes

- Viabilidade: 95%
- Risco: 39%
- Mitigacao: remover apenas os blocos genericamente institucionais do topo, preservando busca, abas e tabela
- Confianca da auditoria: 93%

Motivo:
- A area tecnica esta bem localizada, porem o termo `quadro inicial` pode significar um subconjunto especifico do hero/resumo

## Ordem recomendada de execucao

1. Fechar `Graduacao` e `Extensao`
2. Limpar `Docentes`
3. Ajustar `Bolsas e oportunidades`
4. Resolver `Pesquisa e Desenvolvimento` com escopo confirmado
5. Finalizar `Departamentos` quando o arquivo-base e a regra de contagem estiverem definidos

## Conclusao

Hoje o conjunto nao esta 100% concluido.

O que parece pronto:
- fotos HEP
- foto adicional de Fisica Moderna
- troca do Lattes do Gilson
- links de fluxograma e ementas
- Sprint 2: remocao da frase inicial de Graduacao
- Sprint 2: frase de abertura de Extensao confirmada

O que ainda demanda acao:
- limpeza de placeholders nas paginas de oportunidades
- limpeza do quadro inicial de Docentes
- retirada da faixa direita em Pesquisa e Desenvolvimento
- complementacao de Departamentos com contagem e links por lista
