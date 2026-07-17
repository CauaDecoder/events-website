# Filas

Jobs e listeners técnicos permanecem na infraestrutura do módulo responsável.
Jobs devem delegar regras aos Application Services e ser desenhados para
idempotência. Redis será o backend de filas supervisionadas pelo Horizon.
