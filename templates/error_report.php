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
<!--ERROR_HEADER-->
<table class="error-report-table">
	<thead>
		<tr>
			<td colspan="2"><!--PREFIX--> : <!--NAME--></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Message:</th>
			<td><!--MESSAGE--></td>
		</tr>
		<tr>
			<th>File:</th>
			<td><!--FILE--> (Line <!--LINE-->)</td>
		</tr>
		<tr>
			<th>Occurred:</th>
			<td><!--OCCURRED--> (<!--TIMESTAMP-->)</td>
		</tr>
	</tbody>
</table>
<!--/ERROR_HEADER-->
<table class="error-report-table">
	<thead>
		<tr>
			<th colspan="2">Stacktrace</th>
		</tr>
	</thead>
	<tbody>
	<!--TRACE_FRAME-->
	<tr>
		<th><!--INDEX--></th>
		<td><!--FILE-->:<!--LINE--> - <!--CLASS--><!--TYPE--><!--FUNCTION-->(<!--ARGS-->)</td>
	</tr>
	<!--/TRACE_FRAME-->
	</tbody>
</table>
<!--DATA_SET_STRING-->
<table class="error-report-table">
	<thead>
		<tr>
			<th colspan="2"><!--NAME--></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2"><!--DATA--></td>
		</tr>
	</tbody>
</table>
<!--/DATA_SET_STRING-->
<!--DATA_SET_ARRAY-->
<table class="error-report-table">
	<thead>
	<tr>
		<th colspan="2"><!--NAME--></th>
	</tr>
	</thead>
	<tbody>
		<!--DATA_SET_FRAME-->
			<tr>
				<th><!--NAME--></th>
				<td><!--DATA--></td>
			</tr>
		<!--/DATA_SET_FRAME-->
	</tbody>
</table>
<!--/DATA_SET_ARRAY-->