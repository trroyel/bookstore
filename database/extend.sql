CREATE TABLE roles (
    id          UUID PRIMARY KEY DEFAULT uuidv7(),
    name        VARCHAR(50) NOT NULL UNIQUE,
    permissions JSON NOT NULL DEFAULT '[]',
    description TEXT,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);