# Módulos

Cada contexto em `app/Modules` segue três camadas:

- `Application`: casos de uso, DTOs, queries e contratos.
- `Domain`: conceitos, regras, eventos, exceções, policies e value objects.
- `Infrastructure`: Eloquent, persistência, filas e integrações.

As entradas HTTP, Livewire e Console dependem da camada `Application`. Um módulo
não deve acessar diretamente os detalhes internos de infraestrutura de outro.
