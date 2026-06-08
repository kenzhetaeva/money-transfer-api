<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260608085447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users, accounts, transactions with enums and relations';
    }

    public function up(Schema $schema): void
    {
        // =========================
        // ENUM TYPES
        // =========================

        $this->addSql("
            CREATE TYPE currency_enum AS ENUM ('KGS', 'USD', 'EUR')
        ");

        $this->addSql("
            CREATE TYPE transaction_status_enum AS ENUM ('pending', 'completed', 'failed')
        ");

        // =========================
        // USERS
        // =========================

        $this->addSql("
            CREATE TABLE users (
                id SERIAL PRIMARY KEY,
                name VARCHAR(32) NOT NULL,
                email VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL
            )
        ");

        $this->addSql("
            CREATE UNIQUE INDEX uniq_users_email ON users (email)
        ");

        // =========================
        // ACCOUNTS
        // =========================

        $this->addSql("
            CREATE TABLE accounts (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL,
                currency currency_enum NOT NULL,
                balance NUMERIC(18,2) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL,
                CONSTRAINT fk_accounts_user
                    FOREIGN KEY (user_id)
                    REFERENCES users (id)
                    ON DELETE CASCADE
            )
        ");

        $this->addSql("
            CREATE INDEX idx_accounts_user_id ON accounts (user_id)
        ");

        // =========================
        // TRANSACTIONS
        // =========================

        $this->addSql("
            CREATE TABLE transactions (
                id SERIAL PRIMARY KEY,
                from_account_id INTEGER NOT NULL,
                to_account_id INTEGER NOT NULL,
                amount NUMERIC(18,2) NOT NULL,
                status transaction_status_enum NOT NULL,
                created_at TIMESTAMP NOT NULL,
                CONSTRAINT fk_transactions_from_account
                    FOREIGN KEY (from_account_id)
                    REFERENCES accounts (id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_transactions_to_account
                    FOREIGN KEY (to_account_id)
                    REFERENCES accounts (id)
                    ON DELETE CASCADE
            )
        ");

        $this->addSql("
            CREATE INDEX idx_transactions_from_account ON transactions (from_account_id)
        ");

        $this->addSql("
            CREATE INDEX idx_transactions_to_account ON transactions (to_account_id)
        ");

        $this->addSql("
            CREATE INDEX idx_transactions_created_at ON transactions (created_at)
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE transactions");
        $this->addSql("DROP TABLE accounts");
        $this->addSql("DROP TABLE users");

        // drop enums
        $this->addSql("DROP TYPE transaction_status_enum");
        $this->addSql("DROP TYPE currency_enum");
    }
}