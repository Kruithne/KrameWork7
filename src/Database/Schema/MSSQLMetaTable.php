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
			$this->db->execute("
IF NOT EXISTS (
	SELECT COUNT(*)
	FROM sys.schemas 
	JOIN sys.tables ON tables.schema_id = schemas.schema_id 
	WHERE schemas.name = :schema AND tables.name = :table
)
CREATE TABLE {$this->getFullName()}(
	[table] [VARCHAR](50) NOT NULL,
	[version] [INT] NOT NULL
) ON [PRIMARY]
GO
",
				['schema' => $this->getSchema(), 'table' => $this->getName()]
			);
			$this->setVersion($this->getName(), 1);
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