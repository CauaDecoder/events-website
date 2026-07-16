# ✨ Convitê — Plataforma de Convites Digitais

Plataforma gratuita para criar, personalizar e compartilhar convites digitais interativos para qualquer tipo de evento — casamentos, aniversários, formaturas, chás de bebê e mais.

> **Status:** MVP em construção · Landing Page ✅ · Convite Público 🔜

---

## 🧱 Arquitetura

O projeto é um **monorepo pnpm** com duas aplicações:

```
events_website/
├── apps/
│   ├── web/          # Frontend — Next.js 15 (App Router, TypeScript)
│   └── api/          # Backend  — FastAPI (Python 3.12+)
├── docs/             # Decisões de produto e handoffs
├── design_inspiration/  # Referências visuais (não versionadas em prod)
├── scripts/          # Scripts auxiliares de dev
└── package.json      # Workspace root
```

### Stack

| Camada | Tecnologia | Notas |
|---|---|---|
| **Frontend** | Next.js 15, React 19, TypeScript | Vanilla CSS (sem Tailwind), fontes Fraunces + Manrope + IBM Plex Mono |
| **Ícones** | lucide-react | Ícones SVG otimizados |
| **Backend** | FastAPI, Pydantic v2 | Repositório in-memory por enquanto (PostgreSQL planejado) |
| **Gerenciador** | pnpm 10 | Monorepo via pnpm workspaces |

---

## 🚀 Como rodar localmente

### Pré-requisitos

- **Node.js** ≥ 18 ([download](https://nodejs.org/))
- **pnpm** ≥ 10 (`npm install -g pnpm` ou `corepack enable`)
- **Python** ≥ 3.12 ([download](https://www.python.org/downloads/)) — apenas se for rodar o backend

### 1. Clone o repositório

```bash
git clone https://github.com/SEU-USUARIO/events_website.git
cd events_website
```

### 2. Instale as dependências do frontend

```bash
pnpm install
```

### 3. Rode o frontend (Next.js)

```bash
pnpm dev:web
```

Acesse: **http://localhost:3000**

### 4. (Opcional) Rode o backend (FastAPI)

```bash
cd apps/api
python -m venv .venv

# Windows
.venv\Scripts\activate

# macOS / Linux
source .venv/bin/activate

pip install -e .
uvicorn app.main:app --reload --host 127.0.0.1 --port 8000
```

Acesse:
- API: **http://localhost:8000**
- Health check: **http://localhost:8000/health**
- Swagger (docs interativos): **http://localhost:8000/docs**

### 5. Rode tudo junto (frontend + backend)

```bash
pnpm dev
```

> **Nota:** Este comando usa o script `scripts/dev.mjs` para subir ambos os servidores simultaneamente. Pode falhar em alguns ambientes Windows — nesse caso, rode cada serviço separadamente (passos 3 e 4).

---

## 🎨 Design System

O projeto segue um design system baseado em **Vanilla CSS** com variáveis customizadas:

- **Light mode** (padrão): tons terrosos suaves — fundo `#f5efe5`, acentos terracota `#8f4c3f`
- **Dark mode**: fundo `#0f1115` com acentos terracota claro `#e58775`
- **Toggle**: o usuário alterna entre temas e a preferência é salva no `localStorage`

Referências visuais (na pasta `design_inspiration/`):
- Nomad, Instaclustr → direção da landing page
- Aislinn Kate → tom visual dos convites (elegante, tons terrosos)
- Amanah, Untitled UI, Origin → painel do organizador

---

## 📁 Scripts disponíveis

| Comando | O que faz |
|---|---|
| `pnpm dev:web` | Sobe o frontend (Next.js) em modo dev |
| `pnpm build:web` | Build de produção do frontend |
| `pnpm dev` | Sobe frontend + backend simultaneamente |

---

## 🗺️ Decisões do MVP

- **Multi-evento** desde o MVP (casamento, aniversário, formatura, debutante, chá de bebê, corporativo)
- **RSVP aberto** — convidados não precisam criar conta
- **Módulos ativáveis**: RSVP, Lista de Presentes, Contagem Regressiva, Agenda/Timeline, FAQ
- **Autenticação** (e-mail + senha, Google OAuth) — planejada, ainda não implementada
- **Monetização**: AdSense apenas no painel do organizador (fora do MVP)
- **Pagamentos**: fora do MVP
- **Idioma inicial**: PT-BR

---

## 📋 Roadmap

- [x] Landing Page (Hero, Tipos de Evento, Funcionalidades, Como Funciona, Footer)
- [x] Dark/Light mode com persistência
- [ ] Página do Convite Público (`/evento/[slug]`)
- [ ] Painel do Organizador (dashboard, criar/editar evento)
- [ ] Autenticação (e-mail + senha, Google OAuth)
- [ ] Migração para PostgreSQL + SQLAlchemy/SQLModel
- [ ] Deploy (Vercel + Railway/Render)

---

## 🤝 Contribuindo

1. Crie uma branch a partir da `main`: `git checkout -b feat/minha-feature`
2. Faça suas alterações seguindo o design system existente
3. Rode `pnpm build:web` para garantir que não há erros de TypeScript
4. Abra um Pull Request descrevendo o que mudou

### Convenções

- **CSS**: Vanilla CSS com variáveis (sem Tailwind, sem CSS-in-JS)
- **Componentes**: um arquivo por componente em `apps/web/components/`
- **Seções da landing**: em `apps/web/components/sections/`
- **Commits**: mensagens descritivas em português ou inglês

---

## 📄 Licença

Este projeto ainda não possui uma licença definida. Todos os direitos reservados por enquanto.
