# Arquitetura Inicial

## Objetivo

Construir uma plataforma de convites digitais gratuita para o público final, com um painel do organizador capaz de gerar convites, acompanhar RSVP e evoluir por módulos.

## Princípios

- O evento é a unidade principal do sistema.
- Módulos são ativados por configuração, não por tipo fixo de evento.
- O convite público deve ser mobile-first.
- O painel do organizador pode monetizar, mas a página pública não.

## Modelo conceitual

- `User`: organizador autenticado
- `Event`: evento configurável
- `EventModule`: módulo ativado no evento
- `Guest`: convidado
- `RSVP`: confirmação de presença
- `Message`: recado no mural
- `Gift`: item de lista de presentes ou link externo

## Fases

- Fase 1: login, criação de evento, convite público, RSVP e painel
- Fase 2: lista de presentes externa e mural moderado
- Fase 3: pagamentos externos e monetização no painel
- Fase 4: galeria, mapa, FAQ, temas e contagem regressiva

