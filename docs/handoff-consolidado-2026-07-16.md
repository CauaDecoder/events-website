# Handoff Consolidado: Plataforma de Convites Digitais

Data: 2026-07-16
Este documento une dois handoffs anteriores em um único ponto de referência:
1. `handoff-events-website-2026-07-16.md` — estado técnico real do código, gerado pelo Codex.
2. `handoff-decisions.md` — decisões de produto/arquitetura, gerado por uma sessão de entrevista
   estruturada (grill-me) via Antigravity.

Objetivo: dar ao próximo agente uma visão única do que já existe, do que foi decidido, e resolver
os pontos onde os dois documentos não batem exatamente.

---

## 1. Estado técnico atual (herdado do handoff do Codex)

Monorepo em `events_website/` com:
- Frontend Next.js em `apps/web` — landing inicial funcional (hero, preview de convite, seções de
  MVP/arquitetura), sem integração real com a API.
- Backend FastAPI em `apps/api` — repositório **em memória** (`InMemoryEventRepository`), sem banco,
  sem auth. Endpoints já existentes: `GET /health`, `GET/POST /api/v1/events`,
  `GET /api/v1/events/{slug}`, `GET/POST /api/v1/events/{slug}/rsvps`. Evento seed: `marina-e-lucas`.
- Ambiente local (Windows/Codex) foi estabilizado depois de vários problemas de PATH, build de
  dependências nativas (`sharp`) e um bug de import (`repo` inexistente). Validado: frontend em
  `127.0.0.1:3000` (200) e API em `127.0.0.1:8000/health` (ok), mas só via launchers auxiliares
  (`run-web.cmd`/`run-api.cmd`), não via `pnpm dev` combinado.
- Pendências técnicas reais: integrar frontend↔API, persistência real, auth, painel do organizador,
  modelagem de módulos ativáveis, limpeza dos launchers auxiliares.
- Risco de mojibake em texto PT-BR lido via PowerShell — checar encoding real dos arquivos antes de
  mexer em copy/docs.
- Pode haver processos de dev antigos ainda rodando em background nas portas 3000/8000.

---

## 2. Decisões de produto/arquitetura consolidadas (herdadas do grill-me)

- **Modelo de 3 camadas**: Landing Page (comercial) → Painel do Organizador (logado) → Convite
  Público (página do evento, sem login para o convidado).
- **Multi-evento desde o MVP**: casamento, aniversário, formatura, debutante, chá de bebê,
  corporativo — todos compartilhando a mesma engine de módulos, com templates visuais próprios por
  tipo.
- **Customização visual**: modelo híbrido — templates pré-montados por tipo de evento, com troca de
  paleta/fonte pelo organizador. Sem editor drag-and-drop no MVP.
- **Auth**: e-mail+senha **e** Google OAuth desde o início, apenas para organizadores.
- **Banco de dados**: PostgreSQL com SQLAlchemy/SQLModel, substituindo o repositório em memória.
- **Deploy**: adiado — foco total em dev local por enquanto.
- **Idioma**: PT-BR apenas, sem i18n no MVP.
- **Nome/marca**: ainda não definido, usar nome provisório.
- **Pagamento**: fora do MVP (consistente com a decisão original de deixar essa feature para uma
  fase posterior por causa da complexidade de gateway/compliance).
- **Estilização**: Vanilla CSS, sem Tailwind. Fontes: Fraunces (títulos), Manrope (corpo), IBM Plex
  Mono (labels/monospace).
- **Light/dark mode** com toggle, light como padrão.
- Seções do convite público definidas: Hero (nomes + contagem regressiva), Detalhes (data/local/mapa),
  Agenda/Timeline, RSVP (aberto, sem cadastro do convidado), Lista de Presentes (links externos),
  FAQ.
- Painel do organizador definido: dashboard com métricas, criar/editar evento, lista de convidados
  filtrável/exportável, liga-desliga de módulos, preview do convite, configurações (slug/privacidade/
  prazo de RSVP), suporte a múltiplos eventos por organizador.
- Landing page definida: Hero com CTA, Tipos de Evento (cards), Funcionalidades (grid de módulos),
  Como Funciona (3 passos), Footer.
- Prioridade de implementação definida: 1) Landing Page, 2) Convite Público, 3) Painel do
  Organizador, 4) Autenticação, 5) API + Banco.

---

## 3. Conflitos entre os dois documentos — TODOS RESOLVIDOS

Resolvidos em sessão de grill-me em 16/07/2026. Cada item abaixo tem a decisão final.

### 3.1 Mural de recados ✅ RESOLVIDO — FORA DO MVP
O handoff do Codex tinha "mural moderado" como parte do escopo. A sessão de decisões não o incluiu
nas seções do convite público (Hero, Detalhes, Agenda, RSVP, Lista de Presentes, FAQ).
→ **Decisão final:** Mural de recados foi **removido do MVP de propósito**. O foco são as 6 seções
escolhidas. O mural pode ser adicionado como módulo em uma fase posterior.

### 3.2 Casamento vs. multi-evento ✅ RESOLVIDO — AMBOS
O Codex registrava "casamento como primeiro caso de validação". O grill-me definiu "multi-evento
desde o MVP" como diferencial.
→ **Decisão final:** Casamento continua sendo o caso de referência para validar a engine (seed de
dados existente), mas o MVP nasce com múltiplos tipos de evento e templates visuais próprios por tipo.

### 3.3 Ordem de implementação ✅ RESOLVIDO — UI PRIMEIRO
O Codex sugeria conectar API cedo. O grill-me definiu UI completa primeiro.
→ **Decisão final:** Landing → Convite Público → Painel → Auth → API+Banco. A API in-memory atual
serve como stub temporário enquanto a UI é construída. Dados mockados/estáticos são aceitáveis.

### 3.4 Backend desalinhado ✅ RESOLVIDO — TRABALHO PENDENTE
O backend é `InMemoryEventRepository`, sem banco. PostgreSQL + SQLAlchemy/SQLModel já foi decidido.
→ **Decisão final:** Não é conflito de decisão, é lacuna de implementação. A migração acontece na
etapa 5 do plano de ação (após toda a UI estar pronta).

### 3.5 Referências visuais ✅ RESOLVIDO — CONFIRMADAS
O handoff de decisões citava assets de inspiração que o Codex não mencionava.
→ **Decisão final:** Todos os 7 arquivos existem em `design_inspiration/` e foram confirmados como
referência visual válida:
- `insp-1.png` — Nomad (landing moderna, hero com shapes coloridos)
- `insp-2.png` — Cellares (hero imersivo, dark mode)
- `insp-3.png` — Instaclustr (landing clean, CTA forte)
- `insp-4.png` — Aislinn Kate (fotografia casamento, elegância, tons terrosos)
- `insp-dashboard-1.webp` — Amanah (dashboard dark mode, cards de métricas)
- `insp-dashboard-2.webp` — Untitled UI (settings/billing, sidebar clean)
- `insp-dashboard-3.png` — Origin (dashboard financeiro, sidebar + grid)

---

## 4. Plano de ação priorizado para a próxima sessão

Todos os conflitos foram resolvidos. A implementação pode começar diretamente:

1. Reconstruir/expandir a **Landing Page** para bater com a especificação de seções (Hero, Tipos de
   Evento, Funcionalidades, Como Funciona, Footer), aplicando Vanilla CSS + fontes definidas +
   toggle de light/dark.
2. Construir o **Convite Público** como página real por slug (`/evento/[slug]`), com as 6 seções
   definidas (Hero, Detalhes, Agenda, RSVP, Presentes, FAQ), usando dados mockados ou a API em
   memória como stub.
3. Construir o **Painel do Organizador** (dashboard, criar/editar evento, lista de convidados,
   liga-desliga de módulos, preview, configurações).
4. Implementar **autenticação** (e-mail+senha e Google OAuth) para organizadores.
5. Migrar para **PostgreSQL + SQLAlchemy/SQLModel**, substituindo o repositório em memória, e
   conectar tudo de ponta a ponta.

---

## 5. Riscos e pendências herdadas (ainda válidas)

- API em memória perde dados a cada restart — não é um problema até a etapa 5 do plano acima, mas
  não usar para nada que precise persistir de verdade antes disso.
- Verificar codificação real dos arquivos PT-BR antes de editar copy/docs (risco de mojibake via
  PowerShell).
- Verificar se processos de dev anteriores ainda ocupam as portas 3000/8000 antes de relançar.
- Fluxo combinado (`pnpm dev`) ainda não foi validado como confiável em qualquer contexto — os
  launchers auxiliares (`run-web.cmd`, `run-api.cmd`) são o caminho testado por enquanto.
- Nome/marca do produto ainda não definido — não bloqueia desenvolvimento, mas vai exigir busca de
  domínio/slug mais adiante.

## 6. Skills sugeridas para a próxima sessão

- `frontend-design`: para landing, convite público e painel do organizador.
- `webapp-testing`: para validar a integração real frontend↔API quando ela acontecer.
- `handoff`: novamente ao fim da próxima sessão.

> **Nota:** Todas as ambiguidades de escopo foram resolvidas via `grill-me` em 16/07/2026.
> Não há pendências de decisão — a próxima sessão pode ir direto à implementação.
