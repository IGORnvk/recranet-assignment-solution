<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414235146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, home_team_id INT NOT NULL, guest_team_id INT NOT NULL, referee_id INT NOT NULL, status VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_232B318CC98D86E7 (home_team_id), INDEX IDX_232B318C855A8786 (guest_team_id), INDEX IDX_232B318C31ECF2BC (referee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, home_score INT NOT NULL, guest_score INT NOT NULL, UNIQUE INDEX UNIQ_329937514D77E7D8 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistic (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, played INT NOT NULL, won INT NOT NULL, lost INT NOT NULL, draw INT NOT NULL, goal_difference INT NOT NULL, UNIQUE INDEX UNIQ_649B469CB842D717 (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC98D86E7 FOREIGN KEY (home_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C855A8786 FOREIGN KEY (guest_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C31ECF2BC FOREIGN KEY (referee_id) REFERENCES referee (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937514D77E7D8 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469CB842D717 FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CC98D86E7');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C855A8786');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C31ECF2BC');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937514D77E7D8');
        $this->addSql('ALTER TABLE statistic DROP FOREIGN KEY FK_649B469CB842D717');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE referee');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP TABLE team');
    }
}
