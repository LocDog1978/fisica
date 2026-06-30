# Revisão Geral do Site e Planejamento em Sprints

Data da auditoria inicial: 2026-06-25  
Escopo: ortografia pt-BR, integridade das páginas, página de Técnicos e galerias de laboratórios  
Status: Sprints 1 a 7 concluídas; revisão geral concluída

## Resumo executivo

- [x] Inventário das páginas publicadas realizado
- [x] Respostas HTTP das 48 páginas publicadas verificadas
- [x] Estrutura atual de Técnicos comparada com Corpo Docente
- [x] Sete galerias de laboratórios identificadas e medidas
- [x] Problemas objetivos iniciais registrados
- [x] Correção ortográfica pt-BR concluída nas superfícies públicas auditadas
- [x] Correção dos erros funcionais confirmados na Sprint 1
- [x] Auditoria funcional das páginas concluída na Sprint 3
- [x] Harmonização final da página de Técnicos
- [x] Padronização e otimização final das galerias
- [x] Desempenho das galerias estabilizado
- [x] Revisão regressiva após as implementações

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
- A terminação incompleta `2026/` em Solicitações Especiais foi corrigida para `2025/2` na Sprint 3, em conformidade com o PDF vinculado.

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

Status: concluída em 2026-06-25, com dependências externas e editoriais registradas.

### Estado já confirmado

- [x] 48 de 48 páginas publicadas responderam com HTTP `200`.
- [x] Nenhuma página pública principal retornou `404` ou `500`.
- [x] Uma imagem quebrada foi encontrada.
- [x] Endpoints técnicos `oEmbed` e `xmlrpc.php` foram separados de erros de página.

### Verificações concluídas

- [x] Validar links externos com relatório por domínio.
- [x] Classificar links `href="#"` entre controles legítimos e placeholders.
- [x] Verificar imagens, PDFs e documentos em todas as páginas.
- [x] Verificar menus em desktop e mobile.
- [x] Verificar formulários, botões, abas, busca e modais.
- [x] Verificar páginas vazias ou com conteúdo insuficiente.
- [x] Verificar console JavaScript em páginas representativas.
- [x] Verificar HTML duplicado, IDs repetidos e elementos inacessíveis.
- [x] Verificar contraste, foco, textos alternativos e navegação por teclado em nível estrutural.
- [x] Encaminhar a repetição da auditoria para a Sprint 7, após as alterações das galerias.

### Resultados da auditoria

- 48 páginas publicadas carregadas com sucesso.
- 204 links e recursos internos únicos validados, sem falhas.
- 83 links externos únicos verificados.
- 82 links externos responderam com HTTP `200`.
- O domínio `www.posif.uerj.br` não possui resolução DNS no momento da auditoria.
- 144 ocorrências de `href="#"` foram classificadas como controles legítimos dos submenus `Ensino`, `Área do Aluno` e `Bolsas`.
- Links vazios: `0`.
- IDs HTML duplicados: `0`.
- Alvos `aria-controls` ausentes: `0`.
- Botões sem nome acessível: `0`.
- Gatilhos de modal sem diálogo correspondente: `0`.
- Imagens sem texto alternativo após as correções: `0`.

### Correções implementadas

- O título incompleto de Solicitações Especiais foi corrigido de `2026/` para `2025/2`, em conformidade com o PDF oficial vinculado.
- As 29 imagens editoriais das notícias receberam texto alternativo contextual baseado no título da notícia.
- Os dois logos institucionais receberam o texto alternativo `Instituto de Física da UERJ`.
- Os metadados dos anexos foram atualizados e os caches dos templates relacionados foram limpos.

### Teste em navegador

Foram executados testes com Chrome headless em desktop e mobile:

- Home
- Corpo Docente
- Extensão
- Galeria LFE

Resultado:

- Código de saída do navegador: `0` em todos os testes.
- DOM completo: confirmado.
- Componentes principais: confirmados.
- Erros graves de console ou carregamento: `0`.

### Foco e acessibilidade

- Foram encontrados estilos `:focus-visible` para cartões, links, carrossel, linha do tempo, abas e painéis de Docentes.
- A navegação estrutural por teclado possui alvos e rótulos coerentes.
- Uma medição automatizada completa de contraste visual deverá ser repetida na Sprint 7 após todas as alterações de layout.

### Páginas com conteúdo insuficiente

As páginas abaixo respondem com HTTP `200`, mas não possuem conteúdo principal:

- `Contato`
- `Área do Aluno`
- `Departamentos`
- `Pessoas`

As páginas de galerias e oportunidades também possuem pouco texto, mas isso corresponde ao formato atual solicitado.

### Dependências não alteradas

- O link `https://www.posif.uerj.br/` foi preservado porque não foi encontrada uma URL oficial alternativa no projeto.
- A correção desse link depende da confirmação do endereço oficial da Pós-Graduação.
- As quatro páginas de navegação vazias dependem de decisão editorial: criar páginas de entrada, redirecionar para um destino existente ou removê-las como links clicáveis.

Viabilidade: 95%  
Risco: 36%  
Mitigação: 92%  
Confiança: 93%

Risco residual:

- Uma resposta HTTP `200` não garante que o componente interativo esteja funcionando.
- Alguns problemas só aparecem em navegador real, dispositivos móveis ou no servidor de produção.
- A auditoria regressiva final permanece na Sprint 7 porque precisa medir o estado resultante das Sprints 5 e 6.

## Sprint 4 - Página de Técnicos

Objetivo: deixar Técnicos coerente com Corpo Docente sem apagar dados existentes.

Status: concluída em 2026-06-25.

### Estado atual

- [x] Técnicos já usa `fisica-docentes-page`, hero, painel e tabela do padrão de Docentes.
- [x] A página renderiza 20 servidores.
- [x] A tabela possui Nome, Cargo, Função e Sala.
- [x] O HTML público está acentuado corretamente.
- [x] O quadro-resumo genérico foi removido.
- [x] A página possui busca por todos os campos da tabela.
- [x] Abas não foram criadas porque não existe agrupamento real e confiável nos dados disponíveis.
- [x] A estrutura foi comparada e mantida compatível com o padrão responsivo de Docentes.

### Implementação concluída

- [x] Preservar os 20 registros e a ordem atual.
- [x] Preservar cargo, função e sala.
- [x] Remover informações genéricas ou técnicas sobre a construção da página.
- [x] Adicionar busca por nome, cargo, função e sala.
- [x] Reutilizar o comportamento responsivo da tabela de Docentes.
- [x] Definir abas somente se existir agrupamento real e confiável.
- [x] Não inventar setor, departamento ou vínculo.
- [x] Validar desktop, tablet e mobile.

Observação importante:

- A data editorial `29/04/2026` foi preservada no texto de apoio da busca.
- A referência técnica ao material original foi removida.

### Evidências de validação

- O HTML público contém exatamente 20 linhas de servidores.
- A ordem foi preservada, de `ALEX ALVES DE SOUZA` a `DOUGLAS MILANEZ MARQUES`.
- A busca foi validada com nome, termo sem acento (`valadao`) e termo sem resultado.
- O filtro ignora diferenças de maiúsculas, minúsculas e acentuação.
- O estado vazio e as mensagens de singular e plural foram validados em navegador real.
- A página foi carregada em resolução móvel de `390 x 844` sem erro grave no Chrome headless.
- Não foram criadas abas, setores, departamentos ou vínculos sem fonte confiável.

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

Status: concluída em 2026-06-26.

### Galerias identificadas

- [x] Fotos LEF
- [x] Fotos LFPN
- [x] Fotos LFM
- [x] Fotos HEPGrid
- [x] Fotos LIETA
- [x] Fotos LFE
- [x] Fotos LFMédicas

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

### Resultado após implementação

| Galeria | Imagens | `srcset` | Volume aproximado da grade | Maior imagem da grade |
|---|---:|---:|---:|---:|
| LEF | 12 | 12 | 1,49 MB | 148 KB |
| LFPN | 12 | 12 | 1,29 MB | 128 KB |
| LFM | 12 | 12 | 1,34 MB | 133 KB |
| HEPGrid | 12 | 12 | 1,32 MB | 126 KB |
| LIETA | 12 | 12 | 1,24 MB | 123 KB |
| LFE | 12 | 12 | 1,35 MB | 136 KB |
| LFMédicas | 12 | 12 | 1,51 MB | 141 KB |

### Implementação proposta

- [x] Consolidar todas as galerias no mesmo gerador de markup.
- [x] Manter hero, cores, grade, cantos arredondados e efeito visual atuais.
- [x] Garantir `srcset`, `sizes`, `width`, `height`, `loading` e `decoding`.
- [x] Usar imagem intermediária na grade e imagem maior somente ao abrir.
- [x] Corrigir as duas imagens sem `srcset` em HEPGrid.
- [x] Gerar versões responsivas para as 12 imagens de LFE.
- [x] Evitar carregar originais de alta resolução na grade.
- [x] Padronizar abertura ampliada sem criar um segundo sistema de galeria.
- [x] Confirmar funcionamento estrutural em HTML público; teste visual em navegador fica para a regressão da Sprint 7.

### Evidências de validação

- As sete galerias renderizam exatamente 12 itens cada.
- Todas as 84 imagens de grade possuem `srcset`, `sizes`, `width`, `height`, `loading="lazy"` e `decoding="async"`.
- As 84 imagens de grade usam arquivos intermediários em `assets/images/gallery`, não os originais de `uploads`.
- Os 84 links de abertura ampliada apontam para a versão maior padronizada do mesmo gerador.
- LFE deixou de carregar originais na grade e passou de aproximadamente 6,61 MB para aproximadamente 1,35 MB.
- HEPGrid passou de 10 para 12 imagens com `srcset`.
- O arquivo do otimizador passou em `php -l`, sem erros de sintaxe.

Observação:

- A Sprint 5 padronizou a renderização e reduziu o peso inicial das grades. A remoção de geração/processamento de subimagens durante a requisição pública continua planejada para a Sprint 6.

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

Status: concluída em 2026-06-26.

### Problema técnico confirmado

O otimizador atual pode executar `wp_update_image_subsizes()` durante a requisição pública quando um anexo não possui tamanhos derivados. Isso ajuda a corrigir imagens incompletas, mas pode tornar a primeira visita muito lenta.

Evidência:

- A primeira medição fria de LEF teve aproximadamente 9,7 segundos de TTFB.
- Depois do processamento, as galerias ficaram próximas de 0,7 a 0,9 segundo no ambiente local.
- LFE transferia aproximadamente 6,6 MB na grade antes da Sprint 5.
- Após a Sprint 6, todas as galerias usam variantes responsivas pré-geradas e não executam geração de subimagens durante a requisição pública.

### Implementação proposta

- [x] Gerar tamanhos derivados previamente, fora da requisição pública.
- [x] Remover geração de imagens do caminho de renderização do visitante.
- [x] Validar cache frio e cache quente.
- [x] Medir TTFB, HTML, quantidade de imagens e bytes transferidos.
- [x] Definir limite de peso por imagem da grade.
- [x] Verificar WebP quando suportado pela biblioteca local.
- [x] Manter JPEG como fallback se necessário.
- [x] Não reduzir a imagem ampliada a ponto de prejudicar a visualização.

Meta inicial recomendada:

- Grade inicial abaixo de 2 MB por galeria.
- Imagens da grade preferencialmente abaixo de 180 KB.
- TTFB local estável, sem geração de subimagens durante a visita.

### Resultado após implementação

| Galeria | HTTP | TTFB 1 | TTFB 2 | HTML | Grade | Maior imagem |
|---|---:|---:|---:|---:|---:|---:|
| LEF | 200 | 0,886 s | 0,717 s | 60,9 KB | 1,49 MB | 147,5 KB |
| LFPN | 200 | 0,662 s | 0,711 s | 60,8 KB | 1,29 MB | 127,8 KB |
| LFM | 200 | 0,663 s | 0,695 s | 59,4 KB | 1,34 MB | 132,8 KB |
| HEPGrid | 200 | 0,667 s | 0,686 s | 58,6 KB | 1,32 MB | 125,6 KB |
| LIETA | 200 | 0,660 s | 0,700 s | 59,2 KB | 1,24 MB | 122,8 KB |
| LFE | 200 | 0,680 s | 0,660 s | 59,6 KB | 1,35 MB | 136,4 KB |
| LFMédicas | 200 | 0,661 s | 0,697 s | 59,4 KB | 1,51 MB | 140,9 KB |

### Evidências de validação

- A chamada `wp_update_image_subsizes()` foi removida do otimizador público das galerias.
- O arquivo `05-galerias-laboratorios-optimizer.php` passou em `php -l`.
- As sete galerias responderam com HTTP `200` em duas rodadas consecutivas.
- As 84 imagens de grade continuam com `srcset`, `sizes`, `width`, `height`, `loading="lazy"` e `decoding="async"`.
- Nenhuma imagem de grade usa `wp-content/uploads` como `src`; todas usam variantes em `assets/images/gallery`.
- Todas as grades ficaram abaixo da meta de 2 MB.
- Todas as maiores imagens de grade ficaram abaixo da meta de 180 KB.

Observação:

- WebP não foi adotado nesta sprint porque o PHP local não possui `gd` ou `imagick`, e o ImageMagick CLI não está disponível. O fallback JPEG foi mantido por compatibilidade e por já atingir as metas definidas.

Viabilidade: 92%  
Risco: 52%  
Mitigação: 91%  
Confiança: 94%

## Sprint 7 - Regressão e aceite

Objetivo: confirmar que as correções não criaram novas falhas.

Status: concluída em 2026-06-26.

- [x] Repetir a auditoria funcional completa após todas as implementações.
- [x] Repetir teste HTTP das 48 páginas.
- [x] Repetir auditoria de links e mídias.
- [x] Repetir revisão ortográfica das páginas alteradas.
- [x] Comparar contagem dos 20 técnicos.
- [x] Comparar contagem e dados dos docentes.
- [x] Confirmar 12 imagens em cada galeria.
- [x] Confirmar abertura ampliada das imagens.
- [x] Confirmar busca de Técnicos e Docentes.
- [x] Testar desktop, tablet e mobile.
- [x] Registrar resultados finais neste documento.

### Resultado final da regressão

- 48 de 48 páginas publicadas responderam com HTTP `200`.
- A primeira medição da Home teve TTFB de aproximadamente `9,37 s`, mas três novas rodadas ficaram estáveis entre `0,65 s` e `0,72 s`, indicando cache/frio local e não regressão persistente.
- Foram encontradas 563 referências internas únicas; apenas `xmlrpc.php` retornou `405`, comportamento esperado para endpoint técnico.
- Foram encontradas 142 referências de mídia no HTML público.
- Foram encontradas 84 referências externas; a validação HTTP externa via `curl` local ficou inconclusiva porque o ambiente tentou sair por proxy `127.0.0.1` e retornou `000` para todos os domínios.
- A revisão ortográfica das páginas alteradas não encontrou os termos antigos corrigidos.
- Termos corrigidos confirmados no HTML público: `na pós-graduação`, `Física Aplicada`, `Engenharia de Materiais`, `raios X`, `: um caminho`.
- Técnicos preservou 20 linhas e busca ativa.
- Corpo Docente preservou 75 linhas e busca ativa.
- As sete galerias preservaram 12 itens cada.
- As 84 imagens de galeria preservaram `srcset`, `sizes`, `loading="lazy"` e `decoding="async"`.
- As 84 aberturas ampliadas continuam com link presente.
- Chrome headless confirmou renderização em desktop para Técnicos, tablet para Corpo Docente e mobile para LFE.
- `05-galerias-laboratorios-optimizer.php` passou em validação de sintaxe.
- `git diff --check` não encontrou erro de whitespace; apenas avisos normais de conversão LF/CRLF.

### Pendências não bloqueantes

- Links externos precisam ser revalidados em uma rede sem proxy local quebrado para confirmar status HTTP real dos domínios externos.
- As páginas `Contato`, `Área do Aluno`, `Departamentos` e `Pessoas` continuam com pouco conteúdo principal, conforme dependência editorial já registrada na Sprint 3.
- Uma auditoria visual humana em produção ainda é recomendada antes do aceite definitivo, principalmente para confirmar percepção de layout e abertura de lightbox em dispositivos reais.

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
