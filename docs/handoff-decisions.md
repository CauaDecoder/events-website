# Handoff — Decisões de Produto e Arquitetura

> Gerado em 16/07/2026 via sessão de entrevista estruturada (grill-me).
> Complementa o handoff original do Codex com decisões que redefinem o escopo e a direção do projeto.

---

## Correção de Escopo

O Codex interpretou o projeto como uma aplicação voltada para WhatsApp (convites como mensagens/templates para compartilhar). **A direção correta é uma aplicação web completa:**

- O **convite é uma página web pública** acessível por qualquer navegador via link (ex: `seusite.com/evento/marina-e-lucas`).
- O **organizador gerencia tudo por um painel web logado** (dashboard).
- O link do convite pode ser compartilhado por qualquer canal (WhatsApp, Instagram, e-mail), mas a experiência principal acontece no navegador.

---

## Decisões Consolidadas

### 1. Estrutura da Aplicação (3 Camadas)

| Camada | Descrição | Público |
|---|---|---|
| **Landing Page** | Página comercial que apresenta a plataforma e converte organizadores ao cadastro | Visitantes / potenciais organizadores |
| **Painel do Organizador** | Dashboard logado para criar eventos, acompanhar RSVPs e gerenciar módulos | Organizadores autenticados |
| **Convite Público** | Página pública do evento com informações, RSVP e módulos ativados | Convidados (sem login) |

### 2. Tipos de Evento

- **Multi-evento desde o MVP** — esse é o diferencial da plataforma frente a sites que só fazem templates de casamento.
- Tipos iniciais: casamento, aniversário, formatura, debutante, chá de bebê, corporativo.
- Cada tipo terá templates visuais específicos, mas todos compartilham a mesma engine de módulos.

### 3. Customização Visual dos Convites

- **Modelo híbrido:**
  - Templates visuais pré-montados, organizados por tipo de evento (ex: casamento → tons terrosos, serifadas; aniversário infantil → cores vibrantes, sans-serif).
  - O organizador pode trocar paleta de cores e fontes dentro do template escolhido.
- Sem editor drag-and-drop no MVP.

### 4. Autenticação

- **E-mail + senha** E **Google OAuth** desde o início.
- Apenas organizadores se autenticam; convidados não precisam de conta.

### 5. Banco de Dados

- **PostgreSQL** (relacional, ideal para dados estruturados de eventos/RSVPs/guests).
- Substituir o repositório in-memory atual por SQLAlchemy/SQLModel + PostgreSQL.

### 6. Hospedagem / Deploy

- **Foco no desenvolvimento local por agora.** Decisão de deploy adiada.

### 7. Idioma

- **PT-BR apenas** no MVP. Sem i18n por enquanto.

### 8. Nome / Marca

- **Ainda não definido.** Usar nome provisório durante o desenvolvimento. Trocar depois sem impacto na arquitetura.

---

## Convite Público — Seções do MVP

O convidado acessa o link e vê uma página single-page com as seguintes seções (ativadas como módulos pelo organizador):

| Seção | Descrição |
|---|---|
| **Hero** | Nomes dos anfitriões + contagem regressiva animada |
| **Detalhes** | Data, horário, local com mapa integrado |
| **Agenda / Timeline** | Cronograma do evento (cerimônia, recepção, etc.) |
| **RSVP** | Formulário aberto: nome, e-mail/telefone, acompanhantes, restrições alimentares |
| **Lista de Presentes** | Links externos para lojas |
| **FAQ** | Perguntas frequentes (dress code, estacionamento, etc.) |

**Fluxo de RSVP:** Aberto — qualquer pessoa com o link pode confirmar presença, sem necessidade de cadastro.

---

## Painel do Organizador — Funcionalidades do MVP

| Funcionalidade | Descrição |
|---|---|
| **Dashboard Overview** | Cards com métricas: total de convidados, confirmados, pendentes, recusados |
| **Criar / Editar Evento** | Formulário com dados básicos, upload de imagens, customização visual (template + cores/fontes) |
| **Lista de Convidados** | Tabela filtrável com status de RSVP, opção de exportar |
| **Gerenciamento de Módulos** | Ligar/desligar seções do convite (RSVP, presentes, FAQ, etc.) |
| **Preview do Convite** | Visualizar como o convidado verá a página pública |
| **Configurações** | Slug/URL do evento, privacidade, data limite de RSVP |
| **Multi-eventos** | Um organizador pode gerenciar vários eventos ativos |

---

## Landing Page — Seções

| Seção | Referência Visual |
|---|---|
| **Hero** | Headline impactante + CTA "Crie seu convite grátis" (inspiração: Nomad, Instaclustr) |
| **Tipos de Evento** | Cards visuais com os tipos suportados (casamento, aniversário, formatura, etc.) |
| **Funcionalidades** | Grid com ícones dos módulos (RSVP, presentes, contagem regressiva, etc.) |
| **Como Funciona** | 3 passos: Cadastre → Personalize → Compartilhe |
| **Footer** | Links institucionais e informações |

---

## Estética e Design

- **Light mode + Dark mode** com toggle, sendo light o padrão.
- **Convite público:** estética elegante, tons terrosos, espaço branco generoso (inspiração: `insp-4.png` — Aislinn Kate Photography).
- **Painel do organizador:** limpo e funcional com sidebar de navegação (inspiração: `insp-dashboard-2.webp` e `insp-dashboard-3.png`).
- **Landing page:** moderna e impactante com hero grande (inspiração: `insp-1.png` — Nomad, `insp-3.png` — Instaclustr).
- Fontes atuais: Fraunces (display/títulos), Manrope (corpo), IBM Plex Mono (monospace/labels).

---

## Prioridade de Implementação

1. **Landing Page** — estabelece identidade visual e design system
2. **Convite Público** — experiência do convidado
3. **Painel do Organizador** — funcionalidade do organizador
4. **Autenticação** — login e cadastro
5. **API + Banco** — persistência real

---

## Stack Técnica (confirmada)

| Componente | Tecnologia |
|---|---|
| Frontend | Next.js (TypeScript) |
| Backend | FastAPI (Python) |
| Banco de Dados | PostgreSQL |
| ORM | SQLAlchemy / SQLModel |
| Validação | Pydantic v2 |
| Autenticação | E-mail + senha, Google OAuth |
| Estilização | Vanilla CSS (sem Tailwind) |
| Monorepo | pnpm workspaces |
