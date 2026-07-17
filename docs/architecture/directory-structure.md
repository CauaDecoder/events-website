# Estrutura de diretórios

## Entradas

- `app/Http`: controllers, middleware, validação e representação da API.
- `app/Livewire`: páginas, componentes e Form Objects dos painéis.
- `app/Console`: comandos de entrada pela CLI.

## Núcleo modular

- `app/Modules`: contextos de negócio isolados.
- `app/Support`: infraestrutura transversal sem regras específicas de negócio.
- `app/Rules`: regras de validação Laravel compartilhadas.

## Interfaces e assets

- `resources/views/livewire`: templates dos componentes class-based.
- `resources/views/components`: composição visual própria sobre Flux UI.
- `resources/css`: entradas Tailwind CSS dos painéis.
- `resources/js`: inicialização de Alpine.js e JavaScript transversal mínimo.

## Persistência e qualidade

- `database`: migrations, factories e seeders organizados por contexto.
- `tests/Architecture`: fiscalização das dependências entre camadas.
- `tests/Unit`: regras isoladas.
- `tests/Integration`: Eloquent, Redis, storage e integrações.
- `tests/Feature`: comportamento HTTP, Livewire, console e filas.

## Estado da plataforma

O `composer.json` existente exige Laravel `^13.8`. A arquitetura alvo exige
Laravel 12; a dependência não foi alterada porque esta etapa cria somente o
esqueleto e um downgrade deve ser tratado como mudança explícita de runtime.
