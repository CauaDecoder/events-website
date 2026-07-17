# Tenancy

A estrutura está preparada para banco compartilhado com isolamento lógico por
tenant. A resolução do tenant pertence a `app/Support/Tenancy`; regras e casos
de uso pertencem ao módulo `Tenancy`.

Isolamento deverá ser aplicado por contexto resolvido, autorização, filtros de
persistência, índices no banco e testes contra acesso cruzado.
