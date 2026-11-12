# ==========================================================
# LectoEscritura (EVAL) — esquema y datos base
# ==========================================================
#
# Este directorio es el "contrato de datos" del proyecto: permite
# reconstruir la base de datos desde cero y es el punto de partida
# para una eventual migración a PostgreSQL (Supabase).
#
# ## Contenido
#
#   schema.sql  → estructura (CREATE TABLE) de las 18 tablas.
#                No contiene DROP ni datos.
#   seed.sql    → datos base: dificultades, tipos de texto, niveles de
#                progreso y logros. Sin datos de estudiantes.
#
# ## Reconstruir la base de datos
#
#   mysql.exe -u root --skip-ssl < db\schema.sql
#   mysql.exe -u root --skip-ssl lectoescritura_test < db\seed.sql
#
# En MySQL Workbench: File → Open SQL Script → ejecutar.
#
# ## Nota sobre el historial
#
# El dump original completo (con datos de estudiantes y de admins)
# NO se versiona por privacidad. Si lo necesitás para restaurar,
# guardalo fuera del repositorio.
# ==========================================================
