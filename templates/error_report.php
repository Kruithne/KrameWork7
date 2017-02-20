<!--
	* prefix - Error prefix, such as 'EXCEPTION'.
	* name - Name of the error (exception name, runtime error type, etc).
	* message - Description of the error.
	* timestamp - UNIX timestamp for when the error occurred.
	* occurred - RFC822 date/time of when the error occurred.
	* file - Path to the file which encountered the error.
	* line - Line of code within the file where the error occurred.
	* data - Key/value array containing data sets for this report (values are string or array).
	* trace - Stacktrace of the error (array)
		* file - Path to the file for this stack frame.
		* line - Line of the file for this stack frame.
		* class - Class name for this stack frame.
		* type - Operator type, such as :: for static.
		* function - Name of the function for this stack frame.
		* args - Key/value array of arguments for this frame.
-->
<style type="text/css">
	.error-report-table {
		font-family: "Courier New", Courier, monospace;
		border-collapse: collapse;
		margin-bottom: 10px;
	}

	.error-report-table th, .error-report-table td {
		border: 2px solid gray;
		padding: 4px;
	}

	.error-report-table thead th {
		text-align: left;
		background-color: #798de0;
	}

	.error-report-table tbody th {
		text-align: right;
		background-color: #92bde0;
	}
</style>
<table class="error-report-table">
	<thead>
		<tr>
			<th colspan="2"><?= $prefix; ?> : <?= $name; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Message:</th>
			<td><?= $message; ?></td>
		</tr>
		<tr>
			<th>File:</th>
			<td><?= $file; ?> (Line <?=$line; ?>)</td>
		</tr>
		<tr>
			<th>Occurred:</th>
			<td><?= $occurred; ?> (<?=$timestamp; ?>)</td>
		</tr>
	</tbody>
</table>
<table class="error-report-table">
	<thead>
		<tr>
			<th colspan="2">Stacktrace</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$index = 0;
		foreach ($trace as $frame) {
			$args = [];
			foreach ($frame['args'] ?? [] as $key => $arg)
				$args[$key] = $this->getVariableString($arg);

				$frameFile = $frame['file'] ?? 'interpreter';
				$frameLine = $frame['line'] ?? '?';
				$frameClass = $frame['class'] ?? '';
				$frameType = $frame['type'] ?? '';
				$frameFunction = $frame['function'] ?? '';
			?>
			<tr>
				<th>#<?= $index++; ?></th>
				<td><?= sprintf('%s:%s - %s%s%s(%s)', $frameFile, $frameLine, $frameClass, $frameType, $frameFunction, implode(', ', $args)); ?></td>
			</tr>
			<?php
		}
	?>
	</tbody>
</table>
<?php
	foreach ($data as $setName => $set) {
	?>
	<table class="error-report-table">
		<thead>
			<tr>
				<th colspan="2"><?= $setName; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			if (is_array($set)) {
				if (count($set)) {
					foreach ($set as $nodeKey => $nodeValue) {
						?>
						<tr>
							<th><?= $nodeKey; ?></th>
							<td><?= $this->getVariableString($nodeValue); ?></td>
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<td colspan="2">No data to display.</td>
					</tr>
					<?php
				}
			} else {
				?>
				<td colspan="2"><?= $this->getVariableString($set); ?></td>
				<?php
			}
		?>
		</tbody>
	</table>
	<?php
	}
?>