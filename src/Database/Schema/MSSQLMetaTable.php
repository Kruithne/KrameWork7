<?php

	namespace KrameWork\Database\Schema;

	use KrameWork\Database\Driver\Generic;

	require_once(__DIR__.'/IMetaTable.php');
	require_once(__DIR__.'/../Driver/Generic.php');

	class MSSQLMetaTable implements IMetaTable
	{
		public function __construct(Generic $db)
		{
			$this->db = $db;
			$installed = $this->db->getValue('
SELECT tables.name
FROM sys.schemas
JOIN sys.tables ON tables.schema_id = schemas.schema_id
WHERE schemas.name = ? AND tables.name = ?',
				[$this->getSchema(), $this->getName()]
			);
			if(!$installed) {
				$this->db->execute("SET ANSI_NULLS ON");
				$this->db->execute(
					"CREATE TABLE {$this->getFullName()}([table] [VARCHAR](50) NOT NULL, [version] [INT] NOT NULL, CONSTRAINT [PK__tables] PRIMARY KEY CLUSTERED ([table] ASC)) ON [PRIMARY]",
					[]
				);
				$this->setVersion($this->getName(), 1);
			}
		}

		public function getVersion(string $table): int
		{
			return $this->db->getValue("SELECT [version] FROM {$this->getFullName()} WHERE [table] = ?", [$table]) ?? 0;
		}

		public function setVersion(string $table, int $version)
		{
			$this->db->execute("
INSERT INTO {$this->getFullName()} ([table],[version])
SELECT i.[table], ?
FROM (SELECT ? AS [table]) AS i
LEFT JOIN {$this->getFullName()} AS p ON i.[table] = p.[table]
WHERE p.[table] IS NULL 
",
				[$version, $table]
			);
			$this->db->execute("UPDATE {$this->getFullName()} SET [version]=? WHERE [table]=?", [$version, $table]);
		}

		public function getSchema()
		{
			return 'dbo';
		}

		public function getName()
		{
			return '_tables';
		}

		public function getFullName()
		{
			return "[{$this->getSchema()}].[{$this->getName()}]";
		}

		/**
		 * @var Generic
		 */
		private $db;
	}
