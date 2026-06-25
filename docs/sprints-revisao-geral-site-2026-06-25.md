# Revisão Geral do Site e Planejamento em Sprints

Data da auditoria inicial: 2026-06-25  
Escopo: ortografia pt-BR, integridade das páginas, página de Técnicos e galerias de laboratórios  
Status: auditoria e planejamento concluídos; nenhuma implementação autorizada

## Resumo executivo

- [x] Inventário das páginas publicadas realizado
- [x] Respostas HTTP das 48 páginas publicadas verificadas
- [x] Estrutura atual de Técnicos comparada com Corpo Docente
- [x] Sete galerias de laboratórios identificadas e medidas
- [x] Problemas objetivos iniciais registrados
- [x] Correção ortográfica pt-BR concluída nas superfícies públicas auditadas
- [x] Correção dos erros funcionais confirmados na Sprint 1
- [ ] Harmonização final da página de Técnicos
- [ ] Padronização e otimização final das galerias
- [ ] Revisão regressiva após as implementações

Resultado geral da auditoria:

- Todas as 48 páginas publicadas responderam com HTTP `200`.
- Foi confirmado um erro ortográfico visível em Graduação: `napós-graduação`.
- Foi confirmada uma imagem quebrada na página `Física da Matéria Condensada Teórica`.
- Técnicos já utiliza a linguagem visual de Docentes, mas ainda possui diferenças funcionais e conteúdo genérico.
- As sete galerias já compartilham grade e estilos, mas a entrega responsiva não está uniforme.
- `fotos-lfe` é a galeria com maior problema de desempenho: aproximadamente 6,6 MB apenas nas 12 imagens da grade.

## Definição dos percentuais

Para evitar interpretações diferentes:

- `Viabilidade`: chance técnica de concluir o pedido com os dados e a arquitetura atuais.
- `Risco`: chance de regressão, correção indevida, perda editorial ou alteração de comportamento.
- `Mitigação`: percentual estimado do risco que pode ser controlado com backups, revisão por lotes e testes.
- `Confiança`: força das evidências levantadas nesta auditoria.

Os percentuais não representam tempo de execução.

## Matriz geral

| Recurso | Viabilidade | Risco | Mitigação | Confiança |
|---|---:|---:|---:|---:|
| Correção ortográfica pt-BR | 96% | 48% | 90% | 86% |
| Revisão funcional das páginas | 95% | 36% | 92% | 93% |
| Técnicos semelhante a Docentes | 98% | 24% | 96% | 97% |
| Padronização das galerias | 96% | 42% | 94% | 96% |
| Otimização de velocidade das galerias | 92% | 52% | 91% | 94% |
| Projeto completo | 95% | 40% | 92% | 92% |

## Sprint 0 - Inventário e linha de base

Objetivo: conhecer o estado real antes de alterar conteúdo ou estrutura.

Status: concluída em 2026-06-25, sem implementação.

- [x] Foram encontradas 48 páginas publicadas.
- [x] Todas responderam com HTTP `200`.
- [x] Foram analisadas 312 referências internas únicas.
- [x] Foi localizada uma mídia quebrada com HTTP `404`.
- [x] Foram identificadas sete páginas de galerias.
- [x] Técnicos foi comparada estruturalmente com Corpo Docente.
- [x] Foram registrados tempos e volumes atuais das galerias.

Limitações da linha de base:

- A auditoria ocorreu no ambiente local.
- Tempos locais não equivalem ao servidor de produção.
- Links externos ainda precisam ser validados em uma etapa própria, pois podem sofrer bloqueios, redirecionamentos ou indisponibilidade temporária.
- Não há corretor ortográfico pt-BR instalado no ambiente; a revisão textual precisa combinar automação e leitura contextual.

## Sprint 1 - Correções objetivas e falhas confirmadas

Objetivo: corrigir problemas inequívocos antes da revisão editorial ampla.

Status: concluída em 2026-06-25.

### Ortografia confirmada

- [x] Corrigir `napós-graduação` para `na pós-graduação` na página Graduação.
- [x] Verificar a ocorrência em `post_content` e `_elementor_data`.
- [x] Limpar somente o cache Elementor da página afetada.
- [x] Confirmar a correção no HTML público.

Validação:

- Texto incorreto no HTML público: `0`.
- Texto correto `na pós-graduação`: `2` ocorrências, uma em cada modalidade.
- Links dos fluxogramas de Bacharelado e Licenciatura preservados.

### Imagem quebrada

- [x] Corrigir a referência para `Captura-de-tela-2026-06-10-143850.png`.
- [x] Confirmar se a imagem correta existe na biblioteca com outro nome.
- [x] Não substituir por uma imagem arbitrária.
- [x] Validar a página `Física da Matéria Condensada Teórica` após a correção.

Implementação:

- A referência quebrada foi substituída por `Captura-de-tela-2026-06-10-143850-1-hd.png`.
- O arquivo possui o mesmo nome-base e corresponde à segunda captura científica da sequência.
- A primeira figura `Captura-de-tela-2026-06-10-143834.png` foi preservada.

Validação:

- Referência antiga no HTML público: `0`.
- Referência nova no HTML público: `2`, no link e na imagem.
- Arquivo novo: HTTP `200`.
- Página `Física da Matéria Condensada Teórica`: HTTP `200`.
- Backups de `post_content` e `_elementor_data` criados antes das alterações.

Viabilidade: 99%  
Risco: 14%  
Mitigação: 98%  
Confiança: 99%

Risco principal:

- A imagem original pode não existir mais, exigindo identificação editorial do arquivo correto.

## Sprint 2 - Revisão ortográfica pt-BR

Objetivo: revisar todo texto público sem modificar o significado científico ou institucional.

Status: concluída em 2026-06-25.

### Escopo textual

- [x] Home, cabeçalho, menus e rodapé
- [x] Páginas institucionais
- [x] Graduação e Área do Aluno
- [x] Departamentos
- [x] Pesquisa e Desenvolvimento
- [x] Corpo Docente e Técnicos
- [x] Notícias e eventos
- [x] Extensão
- [x] Galerias, textos alternativos e títulos
- [x] Botões, links, mensagens vazias e campos de busca
- [x] Metadados visíveis e títulos de páginas

### Método recomendado

- [x] Extrair somente texto visível do HTML público.
- [x] Normalizar entidades HTML antes da análise.
- [x] Detectar palavras unidas, espaços duplicados e pontuação inconsistente.
- [x] Detectar sinais reais de codificação corrompida.
- [x] Comparar termos recorrentes para padronizar maiúsculas, siglas e acentuação.
- [x] Revisar manualmente nomes próprios, áreas científicas, salas e títulos oficiais.
- [x] Aplicar correções em lotes pequenos.
- [x] Validar cada lote no banco e no HTML público.

### Correções implementadas

- `Pós Graduação` para `Pós-Graduação` no menu.
- `Menu Toggle` para `Alternar menu` no rótulo acessível do cabeçalho.
- `Pedido de 1º Via` para `Pedido de 1ª Via`.
- `Pré Requisito` para `Pré-requisito`.
- `Avisos Sobre AACC` para `Avisos sobre AACC`.
- `Fisica Aplicada` para `Física Aplicada`.
- `Engenharia de Materias` para `Engenharia de Materiais`.
- `raios x` para `raios X`.
- `: Um caminho` para `: um caminho`.
- `Pós-graduação` para `pós-graduação` em uma função da página Técnicos.
- `e Enfrentamento` para `e enfrentamento`.
- `12 de Março` para `12 de março`.
- `Deputada Estadual` para `deputada estadual`.
- `unidade de Raios X` para `unidade de raios X`.
- `estudo do Efeito Zeeman` para `estudo do efeito Zeeman`.

### Validação

- As 48 páginas continuaram acessíveis durante a extração final.
- Nenhuma página apresentou falha de carregamento na varredura.
- Corpo Docente preservou 75 linhas.
- Técnicos preservou 20 linhas.
- O menu renderiza `Pós-Graduação`.
- O cabeçalho renderiza `aria-label="Alternar menu"`.
- Os termos antigos corrigidos não aparecem mais nas páginas-alvo.
- Os arquivos PHP alterados passaram na validação de sintaxe.

### Casos preservados deliberadamente

- Nomes próprios sem acento não foram alterados sem fonte oficial.
- Slugs históricos, como `ensino-de-fisico`, foram preservados para não quebrar links.
- Títulos oficiais de projetos de Extensão foram preservados.
- Descrições importadas da planilha de Extensão não receberam reescrita gramatical nesta sprint.
- A terminação incompleta `2026/` em Solicitações Especiais foi mantida porque o cartão aponta para um PDF `2025.2`; a divergência foi encaminhada à auditoria funcional.

### Regras de segurança editorial

- Não alterar nomes próprios automaticamente.
- Não traduzir ou simplificar termos científicos.
- Não alterar siglas institucionais.
- Não alterar URLs apenas porque o slug possui grafia antiga.
- Slugs como `ensino-de-fisico` exigem redirecionamento se forem corrigidos; não devem ser renomeados durante uma revisão textual comum.
- Não corrigir citações, títulos oficiais ou nomes de documentos sem fonte.

Viabilidade: 96%  
Risco: 48%  
Mitigação: 90%  
Confiança: 86%

Por que o risco é moderado:

- Corretores automáticos podem marcar como erro nomes, sobrenomes, siglas e vocabulário científico.
- Parte do conteúdo está no Elementor e parte em shortcodes ou PHP.
- Uma substituição global pode afetar HTML, URLs ou dados serializados.

## Sprint 3 - Auditoria funcional completa

Objetivo: identificar páginas quebradas, links órfãos e componentes inconsistentes.

### Estado já confirmado

- [x] 48 de 48 páginas publicadas responderam com HTTP `200`.
- [x] Nenhuma página pública principal retornou `404` ou `500`.
- [x] Uma imagem quebrada foi encontrada.
- [x] Endpoints técnicos `oEmbed` e `xmlrpc.php` foram separados de erros de página.

### Verificações pendentes

- [ ] Validar links externos com relatório por domínio.
- [ ] Classificar links `href="#"` entre controles legítimos e placeholders.
- [ ] Verificar imagens, PDFs e documentos em todas as páginas.
- [ ] Verificar menus em desktop e mobile.
- [ ] Verificar formulários, botões, abas, busca e modais.
- [ ] Verificar páginas vazias ou com conteúdo insuficiente.
- [ ] Verificar console JavaScript em páginas representativas.
- [ ] Verificar HTML duplicado, IDs repetidos e elementos inacessíveis.
- [ ] Verificar contraste, foco, textos alternativos e navegação por teclado.
- [ ] Repetir a auditoria após todas as implementações.

Viabilidade: 95%  
Risco: 36%  
Mitigação: 92%  
Confiança: 93%

Risco residual:

- Uma resposta HTTP `200` não garante que o componente interativo esteja funcionando.
- Alguns problemas só aparecem em navegador real, dispositivos móveis ou no servidor de produção.

## Sprint 4 - Página de Técnicos

Objetivo: deixar Técnicos coerente com Corpo Docente sem apagar dados existentes.

### Estado atual

- [x] Técnicos já usa `fisica-docentes-page`, hero, painel e tabela do padrão de Docentes.
- [x] A página renderiza 20 servidores.
- [x] A tabela possui Nome, Cargo, Função e Sala.
- [x] O HTML público está acentuado corretamente.
- [ ] A página ainda possui quadro-resumo genérico.
- [ ] A página não possui busca.
- [ ] A página não possui abas ou agrupamento.
- [ ] A manutenção dos dados ainda precisa ser comparada com a estrutura de Docentes.

### Implementação proposta

- [ ] Preservar os 20 registros e a ordem atual.
- [ ] Preservar cargo, função e sala.
- [ ] Remover informações genéricas ou técnicas sobre a construção da página.
- [ ] Adicionar busca por nome, cargo, função e sala.
- [ ] Reutilizar o comportamento responsivo da tabela de Docentes.
- [ ] Definir abas somente se existir agrupamento real e confiável.
- [ ] Não inventar setor, departamento ou vínculo.
- [ ] Validar desktop, tablet e mobile.

Observação importante:

- A página já registra a data `29/04/2026` no quadro inicial e informa que os dados vieram de um material original.
- Essa data deve ser preservada apenas se continuar editorialmente relevante.

Viabilidade: 98%  
Risco: 24%  
Mitigação: 96%  
Confiança: 97%

Motivo da alta viabilidade:

- O padrão de Docentes já existe.
- Técnicos já reutiliza as mesmas classes.
- A mudança pode ser estruturalmente pequena e validada por contagem de linhas.

## Sprint 5 - Padronização das galerias

Objetivo: manter um único padrão de renderização sem perder o estilo atual.

### Galerias identificadas

- [ ] Fotos LEF
- [ ] Fotos LFPN
- [ ] Fotos LFM
- [ ] Fotos HEPGrid
- [ ] Fotos LIETA
- [ ] Fotos LFE
- [ ] Fotos LFMédicas

### Estado compartilhado

- [x] Todas possuem uma grade `fisica-gallery-grid`.
- [x] Todas renderizam 12 itens.
- [x] Todas usam `loading="lazy"`.
- [x] Todas possuem texto alternativo.
- [x] Todas possuem dimensões de imagem.
- [x] Todas preservam o estilo visual da página.

### Divergências encontradas

| Galeria | Imagens | `srcset` | Volume aproximado da grade | Maior imagem |
|---|---:|---:|---:|---:|
| LEF | 12 | 12 | 1,56 MB | 151 KB |
| LFPN | 12 | 12 | 1,35 MB | 131 KB |
| LFM | 12 | 12 | 1,41 MB | 136 KB |
| HEPGrid | 12 | 10 | 2,27 MB | 614 KB |
| LIETA | 12 | 12 | 1,30 MB | 126 KB |
| LFE | 12 | 0 | 6,61 MB | 670 KB |
| LFMédicas | 12 | 12 | 1,58 MB | 144 KB |

### Implementação proposta

- [ ] Consolidar todas as galerias no mesmo gerador de markup.
- [ ] Manter hero, cores, grade, cantos arredondados e efeito visual atuais.
- [ ] Garantir `srcset`, `sizes`, `width`, `height`, `loading` e `decoding`.
- [ ] Usar imagem intermediária na grade e imagem maior somente ao abrir.
- [ ] Corrigir as duas imagens sem `srcset` em HEPGrid.
- [ ] Gerar versões responsivas para as 12 imagens de LFE.
- [ ] Evitar carregar originais de alta resolução na grade.
- [ ] Padronizar abertura ampliada sem criar um segundo sistema de galeria.
- [ ] Confirmar funcionamento por teclado e em dispositivos móveis.

Viabilidade: 96%  
Risco: 42%  
Mitigação: 94%  
Confiança: 96%

Riscos:

- Trocar o markup pode interromper a abertura ampliada.
- Imagens que não são anexos reconhecidos pelo WordPress podem ficar sem variantes.
- Regeneração de tamanhos pode consumir tempo e disco.

## Sprint 6 - Desempenho das galerias

Objetivo: melhorar a primeira abertura e impedir processamento pesado durante a visita.

### Problema técnico confirmado

O otimizador atual pode executar `wp_update_image_subsizes()` durante a requisição pública quando um anexo não possui tamanhos derivados. Isso ajuda a corrigir imagens incompletas, mas pode tornar a primeira visita muito lenta.

Evidência:

- A primeira medição fria de LEF teve aproximadamente 9,7 segundos de TTFB.
- Depois do processamento, as galerias ficaram próximas de 0,7 a 0,9 segundo no ambiente local.
- LFE ainda transfere aproximadamente 6,6 MB na grade.

### Implementação proposta

- [ ] Gerar tamanhos derivados previamente, fora da requisição pública.
- [ ] Remover geração de imagens do caminho de renderização do visitante.
- [ ] Validar cache frio e cache quente.
- [ ] Medir TTFB, HTML, quantidade de imagens e bytes transferidos.
- [ ] Definir limite de peso por imagem da grade.
- [ ] Verificar WebP quando suportado pela biblioteca local.
- [ ] Manter JPEG como fallback se necessário.
- [ ] Não reduzir a imagem ampliada a ponto de prejudicar a visualização.

Meta inicial recomendada:

- Grade inicial abaixo de 2 MB por galeria.
- Imagens da grade preferencialmente abaixo de 180 KB.
- TTFB local estável, sem geração de subimagens durante a visita.

Viabilidade: 92%  
Risco: 52%  
Mitigação: 91%  
Confiança: 94%

## Sprint 7 - Regressão e aceite

Objetivo: confirmar que as correções não criaram novas falhas.

- [ ] Repetir teste HTTP das 48 páginas.
- [ ] Repetir auditoria de links e mídias.
- [ ] Repetir revisão ortográfica das páginas alteradas.
- [ ] Comparar contagem dos 20 técnicos.
- [ ] Comparar contagem e dados dos docentes.
- [ ] Confirmar 12 imagens em cada galeria.
- [ ] Confirmar abertura ampliada das imagens.
- [ ] Confirmar busca de Técnicos e Docentes.
- [ ] Testar desktop, tablet e mobile.
- [ ] Registrar resultados finais neste documento.

Viabilidade: 99%  
Risco: 10%  
Mitigação: 99%  
Confiança: 98%

## Ordem recomendada

1. Corrigir o erro ortográfico confirmado e a imagem quebrada.
2. Revisar ortografia por grupos de páginas.
3. Finalizar a equivalência entre Técnicos e Docentes.
4. Corrigir HEPGrid e LFE.
5. Consolidar o padrão das sete galerias.
6. Retirar geração de subimagens da requisição pública.
7. Executar regressão completa.

## Critérios para iniciar a implementação

A implementação só deve começar após autorização explícita.

Sugestão de autorização gradual:

- Autorizar primeiro a Sprint 1, por possuir menor risco.
- Revisar o resultado.
- Autorizar as Sprints 2 e 3.
- Autorizar Técnicos.
- Autorizar as galerias e desempenho.

## Conclusão

O projeto é tecnicamente viável, mas não deve ser tratado como uma substituição global de palavras ou HTML.

Os pontos de maior prioridade são:

- imagem quebrada em Matéria Condensada Teórica;
- erro `napós-graduação`;
- peso e ausência de `srcset` em LFE;
- duas imagens sem `srcset` em HEPGrid;
- geração de tamanhos durante a primeira requisição;
- remoção do conteúdo genérico e inclusão de busca em Técnicos.

Nenhuma dessas alterações foi implementada nesta etapa.
