---
description: Workflow estabelecendo diretrizes de ação, deixando o trabalho direto ao ponto
---

Protocolo de Trabalho Global (Antigravity Global Workflow)

1. Diretriz de Comunicação (Direto ao Ponto & Eficiência de Tokens)
Zero Protelamento: Elimine introduções longas, saudações repetitivas, resumos redundantes do prompt do usuário e conclusões óbvias.

Economia de Tokens: Responda estritamente ao que foi solicitado. Use explicações curtas e código limpo. Se o código fala por si só, reduza o texto explicativo ao mínimo necessário.

Sem Revisões Circulares: Não refaça ou sugira reescrever partes do código que já estão consolidadas, a menos que haja um bug crítico ou falha de segurança explícita. Evite "refatorar por gosto pessoal".

2. Engenharia e Qualidade de Código (Clean Code & Robustez)
Princípio YAGNI (You Aren't Gonna Need It): Escreva apenas o código necessário para a funcionalidade atual. Não adicione complexidade para "funcionalidades futuras" não planejadas.

Princípio DRY (Don't Repeat Yourself): Reutilize lógicas, funções e componentes. Mantenha o código modular e legível.

Tipagem Estrita: Em Python/FastAPI, use Type Hints (Pydantic v2, tipos nativos) de forma rigorosa para garantir auto-documentação e validação em runtime.

Tratamento de Erros: Todo fluxo principal de código deve prever falhas de forma resiliente, retornando códigos de erro HTTP semânticos (FastAPI) e mensagens limpas para o frontend.

3. Segurança desde a Concepção (Security by Design)
Validação de Entrada: Trate toda e qualquer entrada do usuário como hostil. Utilize esquemas do Pydantic para sanitização estrita de dados.

Princípio do Menor Privilégio: Limite permissões de leitura/escrita no banco de dados e APIs externas ao estritamente necessário.

Dados Sensíveis: Nunca trafegue ou armazene senhas em texto puro (utilize hashing seguro com bcrypt/argon2). Proteja segredos industriais e chaves de API utilizando variáveis de ambiente (.env).

Mitigação de Spam/Abuso: Em rotas públicas (como RSVP e mural de recados), implemente rate limiting nativo e moderação ativa.

4. Priorização de Experiência do Usuário (UI/UX)
Mentalidade Mobile-First: Todo layout, componente ou fluxo de telas deve ser projetado prioritariamente para telas sensíveis ao toque e displays verticais (smartphones).

Prevenção de Erros de UX: Crie interfaces informativas que guiem o usuário. Exiba estados de carregamento (loaders) claros e feedbacks instantâneos de sucesso ou erro (Toasts, Modais).

Acessibilidade Básica: Garanta contraste adequado de cores, fontes legíveis e elementos interativos com áreas de toque de no mínimo 44x44 pixels.

5. Ciclo de Vida da Tarefa (Evitar Retrabalho)
Entender o Contexto: Antes de gerar código ou design, valide se a solução proposta respeita o modelo genérico do banco de dados e as restrições do MVP.

Implementar e Validar: Entregue o código pronto para execução (com imports e dependências explícitas).

Consolidar: Uma vez aceito o módulo, as próximas interações devem construir sobre ele, em vez de reescrevê-lo do zero.