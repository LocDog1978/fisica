# Reformulacao das Paginas dos Departamentos em Sprints

Data da analise inicial: 2026-07-08  
Escopo: reformular e reescrever os textos das paginas dos quatro departamentos do Instituto de Fisica da UERJ, sem implementar ainda as alteracoes no site  
Status geral: Sprint 0, Sprint 1, Sprint 2, Sprint 3 e Sprint 4 concluidas; DFT, DFAT, DEQ e DFNAE implementados e validados no ambiente local

## Objetivo

Organizar a execucao em sprints pequenas, com dupla verificacao, para atualizar os textos das paginas:

- DFT - Departamento de Fisica Teorica
- DFAT - Departamento de Fisica Aplicada a Termodinamica
- DEQ - Departamento de Eletronica Quantica
- DFNAE - Departamento de Fisica Nuclear e Altas Energias

## Resumo executivo

- [x] As quatro paginas de departamentos existem no ambiente local.
- [x] Foi confirmado que as paginas atuais ja possuem textos institucionais anteriores.
- [x] Foi confirmado que os textos atuais sao diferentes dos textos novos fornecidos agora.
- [x] Foi confirmado que ainda nao ha implementacao dos novos textos pedidos nesta solicitacao.
- [x] Foi identificado que o menu de Departamentos aponta para estas quatro paginas existentes.
- [x] Foi identificado localmente um indicio forte da distribuicao atual de docentes por departamento na pagina de Corpo Docente.
- [x] O Sprint 1 foi fechado com decisoes editoriais suficientes para iniciar a escrita das paginas.
- [x] Implementar os novos textos nas paginas dos departamentos.
- [x] Confirmar e amarrar os links de "Docentes" por departamento dentro de cada pagina.
- [x] Confirmar e inserir as "Principais linhas de pesquisa" com redacao final aprovada para a implementacao inicial.
- [x] Validar renderizacao final consolidada no HTML publico apos a implementacao completa das quatro paginas.

## Estado atual verificado

### Paginas existentes

- `dft-departamento-de-fisica-teorica`
- `dfat-departamento-de-fisica-aplicada-a-termodinamica`
- `deq-departamento-de-eletronica-quantica`
- `dfnae-departamento-de-fisica-nuclear-e-altas-energias`

### O que ja esta feito hoje

- As paginas ja existem e estao publicadas.
- O menu ja aponta para as paginas corretas.
- Existe uma estrutura visual previa do tipo `fisica-detail-page` para esses departamentos.
- Existe uma pagina de `Corpo Docente` com organizacao por departamento.

### O que ainda nao esta feito

- Nenhuma pendencia tecnica foi identificada dentro do escopo desta demanda no ambiente local.
- Qualquer etapa adicional a partir daqui passa a ser nova solicitacao, como refinamento editorial extra ou validacao em outro ambiente.

## Evidencias levantadas nesta verificacao

### Conteudo anterior ainda presente

Foi confirmado em backups locais das paginas de departamentos que ainda existem textos anteriores, por exemplo:

- DFT com apresentacao institucional generica e secoes como `Apresentacao`, `Area de atuacao`, `Relacao com a UERJ`.
- DFAT com texto anterior centrado em energia, calor e termodinamica aplicada.
- DEQ com texto anterior centrado em optica, fotonica e interacao luz-materia.
- DFNAE com texto anterior centrado em estrutura da materia e interacoes fundamentais.

Conclusao desta checagem:

- O pedido atual nao esta pronto.
- A demanda e de substituicao editorial real, nao apenas ajuste fino.

### Contagens de docentes encontradas localmente

A pagina atual de `Corpo Docente` apresenta, no markup encontrado localmente, os seguintes totais por aba:

- DFAT: `18`
- DEQ: `24`
- DFT: `20`
- DFNAE: `19`

Observacao:

- Isso e uma evidencia local forte para preencher os campos de quantidade, mas a implementacao final deve reutilizar o numero efetivamente vigente na superficie oficial escolhida no momento da alteracao.

## Definicao dos percentuais

- `Viabilidade`: chance tecnica de concluir a etapa com a estrutura atual do site.
- `Risco`: chance de regressao, erro editorial, inconsistencias internas ou quebra de renderizacao.
- `Mitigacao`: percentual estimado do risco que pode ser controlado com verificacao previa, backups e testes.
- `Confianca`: forca das evidencias levantadas nesta analise.

Os percentuais nao representam prazo.

## Matriz geral

| Frente | Viabilidade | Risco | Mitigacao | Confianca |
|---|---:|---:|---:|---:|
| Atualizacao editorial dos quatro departamentos | 97% | 34% | 93% | 94% |
| Preenchimento correto das quantidades de docentes | 92% | 28% | 91% | 86% |
| Insercao de links corretos para docentes por departamento | 90% | 31% | 90% | 82% |
| Insercao das principais linhas de pesquisa | 84% | 49% | 85% | 74% |
| Revisao final e regressao | 96% | 18% | 96% | 92% |
| Projeto completo desta demanda | 92% | 33% | 91% | 89% |

## Analise detalhada de viabilidade, risco, mitigacao e confianca

### 1. Viabilidade tecnica geral: 97%

Motivos:

- As quatro paginas ja existem.
- Ja existe um padrao visual e estrutural para esses departamentos.
- O trabalho parece ser predominantemente editorial, nao uma reconstrucao de layout.
- O ambiente local ja mostrou historico de alteracoes em conteudo Elementor e paginas institucionais.

Fatores que reduzem de 100%:

- Parte do conteudo pedido depende de confirmacoes editoriais.
- Ainda precisamos amarrar corretamente links e linhas de pesquisa.

### 2. Risco geral: 33%

Riscos principais:

- Inserir numero incorreto de docentes em algum departamento.
- Vincular o link de docentes para um destino generico em vez de um filtro ou ancora correta.
- Escrever linhas de pesquisa de forma incompleta ou diferente da referencia institucional esperada.
- Alterar mais do que o necessario na estrutura da pagina.
- Haver cache Elementor ou meta associada que mantenha conteudo antigo no front mesmo apos atualizacao de dados.

Por que o risco nao e baixo:

- O texto novo mistura conteudo pronto com campos ainda em aberto.
- O pedido exige fidelidade institucional.
- Pequenos erros editoriais aqui seriam visiveis e sensiveis.

### 3. Mitigacao geral: 91%

Mitigacoes recomendadas:

- Confirmar antes da implementacao a superficie exata de cada pagina e seus IDs reais.
- Registrar backup do conteudo atual antes de qualquer escrita.
- Tratar quantidades de docentes a partir da fonte local mais confiavel vigente no momento.
- Implementar por lote pequeno, departamento por departamento.
- Validar HTML publico e nao apenas dado salvo.
- Limpar somente cache pontual da pagina alterada se o front ficar stale.
- Manter a estrutura visual existente, alterando apenas o conteudo solicitado.

### 4. Confianca geral: 89%

Por que a confianca e alta:

- A existencia das quatro paginas foi confirmada.
- O menu que aponta para elas foi confirmado.
- O conteudo anterior foi encontrado localmente.
- A pagina de Corpo Docente traz indicios fortes das contagens por departamento.

Por que nao e maior:

- Ainda nao foi fechada a estrategia final dos links de docentes dentro de cada pagina.
- As linhas de pesquisa ainda dependem de consolidacao editorial.

## Sprint 0 - Auditoria e linha de base

Objetivo: confirmar o que ja existe e o que ainda nao foi feito.

Status: concluida em 2026-07-08, sem implementacao no site.

- [x] Confirmar existencia das quatro paginas.
- [x] Confirmar slugs atuais no menu de Departamentos.
- [x] Confirmar que os novos textos ainda nao estao aplicados.
- [x] Confirmar que ha uma estrutura visual existente a preservar.
- [x] Levantar contagens locais de docentes por departamento.
- [x] Registrar dependencias editoriais ainda abertas.

Viabilidade: 100%  
Risco: 6%  
Mitigacao: 99%  
Confianca: 98%

## Sprint 1 - Fechamento editorial antes da escrita

Objetivo: eliminar ambiguidades antes de qualquer alteracao no WordPress.

Status: concluida em 2026-07-08.

- [x] Confirmar a forma final do nome do DFAT na pagina.
- [x] Confirmar os totais finais de docentes por departamento.
- [x] Confirmar o destino exato do link `Docentes` para cada departamento.
- [x] Confirmar a lista final de `Principais linhas de pesquisa` para cada pagina.
- [x] Revisar consistencia institucional das datas historicas mencionadas.

Viabilidade: 94%  
Risco: 39%  
Mitigacao: 88%  
Confianca: 78%

Risco principal:

- Implementar texto com placeholders ou nomenclatura nao validada.

### Resultado fechado do Sprint 1

#### 1. Nome final do DFAT na pagina

Decisao para implementacao:

- Preservar o nome institucional ja existente no site e no menu: `Departamento de Fisica Aplicada a Termodinamica`.

Justificativa:

- O slug atual da pagina ja usa `dfat-departamento-de-fisica-aplicada-a-termodinamica`.
- O menu atual aponta para `DFAT – Departamento de Fisica Aplicada a Termodinamica`.
- Os backups locais da propria pagina usam `DFAT - Departamento de Fisica Aplicada a Termodinamica`.

Conclusao editorial:

- Para evitar divergencia entre menu, slug e titulo da pagina, a implementacao deve manter `a Termodinamica` no titulo principal.
- O texto corrido pode ser ajustado internamente, mas o nome institucional exibido da pagina deve permanecer alinhado com a superficie atual do site.

#### 2. Totais finais de docentes por departamento

Totais fechados para implementacao inicial:

- DFAT: `18`
- DEQ: `24`
- DFT: `20`
- DFNAE: `19`

Base usada:

- Marcadores e contagens encontrados na pagina atual de `Corpo Docente`, organizada por departamento.

#### 3. Destino exato do link `Docentes`

Decisao para implementacao:

- Usar, nas quatro paginas, o destino unico `http://localhost/fisica/index.php/corpo-docente/`.

Justificativa:

- A pagina `Corpo Docente` existe e esta organizada por departamento.
- Foi confirmada a existencia visual das abas por departamento.
- Nao foi confirmada, nesta verificacao, uma mecanica publica ja existente que abra diretamente uma aba especifica por hash, ancora ou query string.

Conclusao editorial e tecnica:

- O destino seguro e verificavel hoje e a pagina geral de `Corpo Docente`.
- Nao devemos inventar links profundos para abas sem evidencia de suporte real no front atual.

#### 4. Principais linhas de pesquisa consolidadas para a implementacao inicial

Lista fechada para o DFT:

- Cosmologia e Gravitacao
- Fisica Hadrônica
- Fisica Matematica e Computacional
- Teoria Quantica de Campos

Lista fechada para o DFAT:

- Fisica Medica
- Ensino de Fisica
- Metrologia da radiacao ionizante e dosimetria
- Aplicacoes da Fisica a ciencias biomedicas, ambientais e tecnicas experimentais

Lista fechada para o DEQ:

- Fisica da Materia Condensada
- Magnetismo
- Optica e Fotonica
- Acustica
- Sensores e dispositivos eletro-opticos
- Modelagem teorica e computacional de materiais

Lista fechada para o DFNAE:

- Fisica de Particulas
- Fisica Experimental de Altas Energias

Observacao importante:

- Essas listas foram consolidadas a partir do texto fornecido por voce e das areas encontradas localmente no `Corpo Docente`.
- Na implementacao, elas devem entrar como secoes editoriais das paginas de departamentos, sem alterar a estrutura da pagina de `Corpo Docente`.

#### 5. Datas historicas mencionadas

Datas mantidas para implementacao:

- DFT: `15 de novembro de 1991`
- DEQ: `15 de outubro de 1991`
- DFNAE: `15 de outubro de 1991`

Conclusao da verificacao:

- Nao foi encontrada, nesta auditoria local, evidencia que contradiga essas datas.
- Portanto, elas podem ser mantidas como base editorial da implementacao inicial.

#### 6. O que continua fora do Sprint 1

- A aplicacao dos novos textos nas paginas ainda nao foi feita.
- A validacao de renderizacao publica so entra depois da implementacao.
- Qualquer comportamento novo de abrir diretamente a aba correta de `Corpo Docente` continua fora do escopo atual, a menos que seja pedido explicitamente.

## Sprint 2 - Reescrita do DFT e do DFAT

Objetivo: atualizar os dois departamentos com menor dependencia de infraestrutura especial.

Status: concluida em 2026-07-08.

- [x] Substituir o texto atual do DFT pelo novo texto aprovado.
- [x] Inserir a quantidade correta de docentes do DFT.
- [x] Inserir a secao `Principais linhas de pesquisa` do DFT.
- [x] Inserir o link correto de `Docentes` do DFT.
- [x] Substituir o texto atual do DFAT pelo novo texto aprovado.
- [x] Inserir a quantidade correta de docentes do DFAT.
- [x] Inserir a secao `Principais linhas de pesquisa` do DFAT.
- [x] Inserir o link correto de `Docentes` do DFAT.
- [x] Validar que a estrutura visual original foi preservada nas duas paginas.

Viabilidade: 97%  
Risco: 27%  
Mitigacao: 93%  
Confianca: 90%

Risco principal:

- Inconsistencia entre o texto aprovado e a forma final do nome do DFAT.

### Implementacao realizada no Sprint 2

Paginas alteradas:

- DFT: pagina `307`
- DFAT: pagina `309`

Backups gerados antes da escrita:

- `wp-content/uploads/elementor-db-backups/department-page-307-post-content-before-20260708-194437.html`
- `wp-content/uploads/elementor-db-backups/department-page-309-post-content-before-20260708-194437.html`

Decisoes efetivamente aplicadas:

- A estrutura visual existente do tipo `fisica-detail-page` foi preservada.
- O DFT passou a exibir `20 docentes permanentes`.
- O DFAT passou a exibir `18 docentes permanentes`.
- A pagina do DFAT manteve o nome institucional alinhado com a superficie atual do site: `Departamento de Fisica Aplicada a Termodinamica`.
- O link de `Docentes` nas duas paginas aponta para `http://localhost/fisica/index.php/corpo-docente/`.
- As secoes `Principais linhas de pesquisa` foram inseridas conforme o fechamento editorial do Sprint 1.

### Evidencias de validacao do Sprint 2

Validacao publica do DFT:

- Presenca confirmada de `Criado em 15 de novembro de 1991`.
- Presenca confirmada de `20 docentes permanentes`.
- Presenca confirmada de `Cosmologia e Gravitacao`.
- Presenca confirmada de `Ver docentes do Instituto`.
- Preservacao confirmada dos marcadores estruturais `fisica-detail-page__hero`, `fisica-detail-page__sidebar` e `fisica-detail-page__highlights`.

Validacao publica do DFAT:

- Presenca confirmada de `18 docentes permanentes`.
- Presenca confirmada de `Fisica Medica`.
- Presenca confirmada de `Pesquisa em Ensino de Fisica`.
- Presenca confirmada de `Ver docentes do Instituto`.
- Preservacao confirmada dos marcadores estruturais `fisica-detail-page__hero`, `fisica-detail-page__sidebar` e `fisica-detail-page__highlights`.

Conclusao do Sprint 2:

- O escopo previsto para DFT e DFAT foi implementado sem indicio de regressao estrutural nas paginas.
- A demanda segue agora para o Sprint 3, focado em DEQ e DFNAE.

## Sprint 3 - Reescrita do DEQ e do DFNAE

Objetivo: atualizar os dois departamentos com maior densidade de detalhes tecnicos e institucionais.

Status: concluida em 2026-07-08.

- [x] Substituir o texto atual do DEQ pelo novo texto aprovado.
- [x] Inserir a quantidade correta de docentes do DEQ.
- [x] Inserir a secao `Principais linhas de pesquisa` do DEQ.
- [x] Inserir o link correto de `Docentes` do DEQ.
- [x] Substituir o texto atual do DFNAE pelo novo texto aprovado.
- [x] Inserir a quantidade correta de docentes do DFNAE.
- [x] Inserir a secao `Principais linhas de pesquisa` do DFNAE.
- [x] Inserir o link correto de `Docentes` do DFNAE.
- [x] Revisar com atencao referencias a `CMS`, `ATLAS`, `CERN` e `T2-HEPGRID-Brasil`.

Viabilidade: 95%  
Risco: 36%  
Mitigacao: 91%  
Confianca: 88%

Risco principal:

- Maior chance de detalhe institucional ou tecnico impreciso se a redacao nao estiver totalmente fechada antes da implementacao.

### Implementacao realizada no Sprint 3

Paginas alteradas:

- DEQ: pagina `311`
- DFNAE: pagina `313`

Backups gerados antes da escrita:

- `wp-content/uploads/elementor-db-backups/department-page-311-post-content-before-20260708-194928.html`
- `wp-content/uploads/elementor-db-backups/department-page-313-post-content-before-20260708-194928.html`

Decisoes efetivamente aplicadas:

- A estrutura visual existente do tipo `fisica-detail-page` foi preservada nas duas paginas.
- O DEQ passou a exibir `24 docentes permanentes`.
- O DFNAE passou a exibir `19 docentes permanentes`.
- O link de `Docentes` nas duas paginas aponta para `http://localhost/fisica/index.php/corpo-docente/`.
- As secoes `Principais linhas de pesquisa` foram inseridas conforme o fechamento editorial do Sprint 1.
- As referencias a `CMS`, `ATLAS`, `CERN` e `T2-HEPGRID-Brasil` foram mantidas na redacao implementada do DFNAE.

### Evidencias de validacao do Sprint 3

Validacao publica do DEQ:

- Presenca confirmada de `Criado em 15 de outubro de 1991`.
- Presenca confirmada de `24 docentes permanentes`.
- Presenca confirmada de `Sensores e dispositivos eletro-opticos`.
- Presenca confirmada de `Ver docentes do Instituto`.
- Preservacao confirmada dos marcadores estruturais `fisica-detail-page__hero`, `fisica-detail-page__sidebar` e `fisica-detail-page__highlights`.

Validacao publica do DFNAE:

- Presenca confirmada de `Criado formalmente em 15 de outubro de 1991`.
- Presenca confirmada de `19 docentes permanentes`.
- Presenca confirmada de `CMS e ATLAS`.
- Presenca confirmada de `T2-HEPGRID-Brasil`.
- Presenca confirmada de `Ver docentes do Instituto`.
- Preservacao confirmada dos marcadores estruturais `fisica-detail-page__hero`, `fisica-detail-page__sidebar` e `fisica-detail-page__highlights`.

Conclusao do Sprint 3:

- O escopo previsto para DEQ e DFNAE foi implementado sem indicio de regressao estrutural nas paginas.
- Com isso, os quatro departamentos previstos nesta demanda ja foram publicados no ambiente local.

## Sprint 4 - Revisao regressiva e aceite

Objetivo: validar que o pedido foi atendido sem alterar mais nada fora do escopo.

Status: concluida em 2026-07-08.

- [x] Revisar as quatro paginas no HTML publico.
- [x] Confirmar preservacao da estrutura visual e dos links de navegacao.
- [x] Confirmar que os textos antigos nao permanecem renderizados.
- [x] Confirmar que as quantidades de docentes renderizadas correspondem ao valor aprovado.
- [x] Confirmar que os links de `Docentes` apontam para o destino correto.
- [x] Confirmar que as `Principais linhas de pesquisa` aparecem nas paginas corretas.
- [x] Registrar evidencias finais neste documento.

Viabilidade: 96%  
Risco: 18%  
Mitigacao: 96%  
Confianca: 92%

### Evidencias finais do Sprint 4

Paginas revisadas no HTML publico:

- `dft-departamento-de-fisica-teorica`
- `dfat-departamento-de-fisica-aplicada-a-termodinamica`
- `deq-departamento-de-eletronica-quantica`
- `dfnae-departamento-de-fisica-nuclear-e-altas-energias`

Confirmacoes consolidadas:

- As quatro paginas continuam renderizando a estrutura `fisica-detail-page`.
- Os marcadores estruturais `fisica-detail-page__hero`, `fisica-detail-page__sidebar` e `fisica-detail-page__highlights` permanecem presentes nas quatro paginas.
- O link de `Docentes` aponta corretamente para `http://localhost/fisica/index.php/corpo-docente/` em todas as quatro paginas.
- Os totais renderizados correspondem ao aprovado: DFT `20`, DFAT `18`, DEQ `24`, DFNAE `19`.
- As secoes `Principais linhas de pesquisa` aparecem nas paginas corretas.
- Os textos antigos sensiveis, como `Area de atuacao` e `Relacao com a UERJ`, nao permanecem renderizados nas quatro paginas revisadas.

Observacao de verificacao:

- Parte das checagens automatizadas por terminal sofreu ruido de codificacao em termos com acentuacao, mas a inspecao direta do HTML bruto confirmou a presenca dos novos trechos nas paginas revisadas.

Conclusao do Sprint 4:

- O pedido foi atendido integralmente no ambiente local, sem indicio de regressao estrutural nas paginas revisadas.
- O escopo desta demanda fica encerrado com aceite tecnico local.

## Ordem recomendada de execucao

1. Fechar nomes, numeros, links e linhas de pesquisa.
2. Implementar DFT e DFAT.
3. Validar.
4. Implementar DEQ e DFNAE.
5. Validar.
6. Fazer regressao final.

## Dupla verificacao recomendada

Antes de implementar:

- Conferir se o texto novo aprovado e exatamente o texto final a publicar.
- Conferir se os campos em aberto foram resolvidos.
- Conferir se os links de docentes serao por aba, ancora, filtro ou pagina de listagem geral.

Depois de implementar:

- Conferir HTML publico.
- Conferir se o conteudo salvo e o conteudo renderizado batem.
- Conferir se nao houve alteracao de layout, menu, sidebar ou elementos nao solicitados.

## Conclusao

O pedido segue tecnicamente viavel e o ambiente local confirmou, na pratica, que a base necessaria existe. O ponto critico continua sendo menos de engenharia pesada e mais de precisao editorial: quantidades corretas, links corretos, linhas de pesquisa corretas e manutencao estrita do layout existente.

Neste momento, os quatro departamentos foram implementados e validados no ambiente local, com preservacao do layout existente e sem evidencias de regressao dentro do escopo solicitado.
