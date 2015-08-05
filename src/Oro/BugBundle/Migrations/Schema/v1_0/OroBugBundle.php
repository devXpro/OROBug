<?php

namespace Oro\BugBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroBugBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroBugIssueTable($schema);
        $this->createCollaboratorsTable($schema);
        $this->createOroBugIssueResolutionTable($schema);
        $this->createOroBugIssueStatusTable($schema);
        $this->createOroBugIssuePriorityTable($schema);

        /** Foreign keys generation **/
        $this->addOroBugIssueForeignKeys($schema);
        $this->addCollaboratorsForeignKeys($schema);
    }

    /**
     * Create oro_bug_issue table
     *
     * @param Schema $schema
     */
    protected function createOroBugIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_bug_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('priority_id', 'integer', []);
        $table->addColumn('status_id', 'integer', []);
        $table->addColumn('resolution_id', 'integer', []);
        $table->addColumn('owner_id', 'integer', []);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', []);
        $table->addColumn('summary', 'string', ['length' => 1000]);
        $table->addColumn('code', 'string', ['length' => 5]);
        $table->addColumn('description', 'string', ['length' => 10000]);
        $table->addColumn('type', 'integer', []);
        $table->addColumn('created', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addIndex(['assignee_id'], 'idx_94d6c30559ec7d60', []);
        $table->addIndex(['status_id'], 'idx_94d6c3056bf700bd', []);
        $table->addIndex(['priority_id'], 'idx_94d6c305497b19f9', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['resolution_id'], 'idx_94d6c30512a1c43a', []);
        $table->addIndex(['parent_id'], 'idx_94d6c305727aca70', []);
        $table->addIndex(['owner_id'], 'idx_94d6c3057e3c61f9', []);
    }

    /**
     * Create collaborators table
     *
     * @param Schema $schema
     */
    protected function createCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_bug_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->addIndex(['issue_id'], 'idx_42dd9f545e7aa58c', []);
        $table->addIndex(['user_id'], 'idx_42dd9f54a76ed395', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
    }

    /**
     * Create oro_bug_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createOroBugIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oro_bug_issue_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create oro_bug_issue_status table
     *
     * @param Schema $schema
     */
    protected function createOroBugIssueStatusTable(Schema $schema)
    {
        $table = $schema->createTable('oro_bug_issue_status');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('open', 'boolean', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create oro_bug_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createOroBugIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('oro_bug_issue_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add oro_bug_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroBugIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_bug_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_bug_issue_priority'),
            ['priority_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_bug_issue_status'),
            ['status_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_bug_issue_resolution'),
            ['resolution_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_bug_issue'),
            ['parent_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
    }

    /**
     * Add collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_bug_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_bug_issue'),
            ['issue_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }
}