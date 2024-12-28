# Changelog

Todas as alterações notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.2.0] - 2024-12-28

### Corrigido
- Corrigido erro 500 na página de Controle de Reforço
- Otimizado carregamento de dados dos tanques usando eager loading
- Melhorada performance da renderização da view

### Modificado
- Refatorado ReforcoController para pré-calcular valores dos tanques
- Simplificada a lógica da view data.blade.php
- Adicionado logging detalhado para melhor diagnóstico

### Técnico
- Implementado eager loading para ciclos dos tanques
- Movida lógica de cálculo da view para o controller
- Adicionadas propriedades calculadas aos objetos dos tanques (soma_peso, excedeu, diferenca)
- Melhorada estrutura de logs para rastreamento de erros

### Arquivos Alterados
- `app/Http/Controllers/ReforcoController.php`
- `resources/views/controle_reforco/data.blade.php`
- `config/logging.php`

## [1.1.0] - 2024-12-28

### Adicionado
- Funcionalidade de exportação para Excel
- Novo botão "Exportar Excel" na interface
- Classe CheckListExport para gerenciar exportações
- Suporte a filtros na exportação

### Técnico
- Instalação do pacote maatwebsite/excel
- Nova rota para exportação
- Implementação de mapeamento de dados para Excel

## [1.0.0] - 2024-12-28

### Adicionado
- Implementação de paginação server-side no DataTables
- Otimização de performance na listagem de dados
- Mensagens de interface em português
- Indicador visual de carregamento durante processamento

### Modificado
- Refatoração do método ajax no CheckListCatalogacaoController
- Melhoria na estrutura de resposta JSON para o DataTables
- Otimização das queries do banco de dados com filtros apropriados

### Corrigido
- Problema de performance no carregamento inicial da tabela
- Tratamento adequado de ordenação e busca
- Formatação correta das datas e status na tabela

### Técnico
- Implementação de lazy loading para melhor performance
- Adição de logging para debug
- Configuração correta do CSRF token para requisições Ajax

## [Não Lançado]

### Para ser implementado
- Sistema de cache para otimizar consultas frequentes
- Filtros avançados de pesquisa
- Interface responsiva para dispositivos móveis

---
Para restaurar para uma versão específica, use:
```bash
git checkout v1.2.0  # Substitua 1.2.0 pela versão desejada
```

Para voltar à versão mais recente:
```bash
git checkout main
```
