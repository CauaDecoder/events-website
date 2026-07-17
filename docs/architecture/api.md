# API

A API REST é versionada em `/api/v1`. Controllers, Form Requests e API Resources
ficam versionados na borda HTTP; módulos e regras de negócio não recebem versão.

Os arquivos em `routes/api/v1` separam endpoints públicos, autenticação, cliente
e administração. O Next.js deverá consumir apenas os endpoints públicos.
