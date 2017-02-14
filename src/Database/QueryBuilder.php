<?php
	namespace KrameWork\Database;

	class QueryBuilder
	{
		/**
		 * QueryBuilder constructor.
		 * @param IDatabaseConnection $db The connection we want to run our query against
		 * @param string $column The name of the column we are searching
		 * @param QueryBuilder $anchor The previous step in the chain
		 * @param IDatabaseTable $table The table we are querying
		 * @param int $level The nth column in the where statement
		 */
		public function __construct(IDatabaseConnection $db, string $column, QueryBuilder $anchor, IDatabaseTable $table, int $level = 1)
		{
			$this->db = $db;
			$this->column = $column;
			$this->anchor = $anchor;
			$this->crud = $table;
			$this->level = $level;
		}

		/**
		 * Builds the SQL statement
		 * @param bool $glue Whether or not more columns will be added later
		 * @return string An SQL fragment
		 */
		public function build($glue = true)
		{
			$base = ($this->anchor ? $this->anchor->build() . ' ' : 'SELECT * FROM ' . $this->crud->getName() . ' WHERE ')
				. sprintf($this->format, $this->column, $this->level)
				. ($glue ? ' ' . $this->glue : '');

			if ($this->query_limit)
			{
				switch ($this->db->getType())
				{
					case 'mssql':
						$base = str_replace('SELECT *', 'SELECT TOP ' . $this->query_limit . ' *', $base);
						break;
				}
			}

			if ($glue)
				return $base;

			if (count($this->orderBy))
			{
				$cols = [];
				foreach ($this->orderBy as $col => $asc)
					$cols[] = $col.' '.($asc?'ASC':'DESC');
				$base .= ' ORDER BY '.join(', ',$cols);
			}

			if ($this->query_limit)
			{
				switch ($this->db->getType())
				{
					case 'mysql':
					case 'pgsql':
					case 'sqlite':
						$base .= sprintf(' LIMIT %d', $this->query_limit);
						break;
				}
			}

			if ($this->query_offset)
			{
				switch ($this->db->getType())
				{
					case 'mysql':
					case 'pgsql':
					case 'sqlite':
						$base .= sprintf(' OFFSET %d', $this->query_limit);
						break;
				}
			}

			return $base;
		}

		/**
		 * Binds the parameters of the prepared statement to the values supplied by the user
		 */
		public function bind($statement)
		{
			if ($this->value !== null)
			{
				if (is_array($this->value))
				{
					foreach ($this->value as $pf => $value)
					{
						$key = $this->column . $this->level . '_' . $pf;
						$statement->$key = $value;
					}
				}
				else
				{
					$key = $this->column . $this->level;
					$statement->$key = $this->value;
				}
			}

			if ($this->anchor)
				$this->anchor->bind($statement);
		}

		public function andColumn($column)
		{
			$this->glue = 'AND';
			return new self($this->db, $column, $this, $this->crud, $this->level + 1);
		}

		public function orColumn($column)
		{
			$this->glue = 'OR';
			return new self($this->db, $column, $this, $this->crud, $this->level + 1);
		}

		public function like($value)
		{
			$this->format = '%1$s LIKE :%1$s%2$s';
			$this->value = $value;
			return $this;
		}

		public function notLike($value)
		{
			$this->format = '%1$s NOT LIKE :%1$s%2$s';
			$this->value = $value;
			return $this;
		}

		public function isNull()
		{
			$this->format = '%1$s IS NULL';
			$this->value = null;
			return $this;
		}

		public function maximum()
		{
			$this->format = '%1$s = (SELECT MAX(%1$s) FROM '.$this->crud->getName().')';
			$this->value = null;
			return $this;
		}

		public function minimum()
		{
			$this->format = '%1$s = (SELECT MIN(%1$s) FROM '.$this->crud->getName().')';
			$this->value = null;
			return $this;
		}

		public function notNull()
		{
			$this->format = '%1$s IS NOT NULL';
			$this->value = null;
			return $this;
		}

		public function lessThan($value)
		{
			$this->format = '%1$s < :%1$s%2$s';
			$this->value = $value;
			return $this;
		}

		public function greaterThan($value)
		{
			$this->format = '%1$s > :%1$s%2$s';
			$this->value = $value;
			return $this;
		}

		public function equals($value)
		{
			$this->format = '%1$s = :%1$s%2$s';
			$this->value = $value;
			return $this;
		}

		public function equalsCaseInsensitive($value)
		{
			$this->format = 'LOWER(%1$s) = :%1$s%2$s';
			$this->value = strtolower($value);
			return $this;
		}

		public function between($low, $high)
		{
			$this->format = '(%1$s > :%1$s%2$s_low AND %1$s < :%1$s%2$s_high)';
			$this->value = array('low' => $low, 'high' => $high);
			return $this;
		}

		public function offset($offset)
		{
			if($this->anchor)
				return $this->anchor->offset($count);
			$this->query_offset = $offset;
			switch($this->db->getType())
			{
				case 'mysql':
				case 'pgsql':
					return $this;
			}
			throw new \Exception('Unsupported database type');
		}

		public function limit($count)
		{
			if($this->anchor)
				return $this->anchor->limit($count);
			$this->query_limit = $count;
			switch($this->db->getType())
			{
				case 'mysql':
				case 'pgsql':
					return $this;
			}
			throw new \Exception('Unsupported database type');
		}

		public function descending($column)
		{
			$this->orderBy[$column] = false;
		}

		public function ascending($column)
		{
			$this->orderBy[$column] = true;
		}

		public function execute()
		{
			$sql = $this->build(false);
			if (!$this->statement)
				$this->statement = $this->db->prepare($sql);

			$this->bind($this->statement);

			$result = array();
			foreach ($this->statement->execute()->getRows() as $data)
				$result[] = $this->crud->getNewObject($data);

			return $result;
		}

		/**
		 * @var DatabaseStatement
		 */
		private $statement;

		/**
		 * @var string AND/OR
		 */
		private $glue;

		/**
		 * @var string column name
		 */
		private $column;

		/**
		 * @var string SQL Fragment
		 */
		private $format;

		/**
		 * @var mixed Search value
		 */
		private $value;

		/**
		 * @var IDatabaseConnection
		 */
		private $db;

		/**
		 * @var int
		 */
		private $level;

		/**
		 * @var QueryBuilder
		 */
		private $anchor;

		/**
		 * @var int
		 */
		private $query_limit;

		/**
		 * @var int
		 */
		private $query_offset;

		/**
		 * @var array
		 * Key is column name, value is bool. False = descending, True = ascending
		 */
		private $orderBy = [];
	}
