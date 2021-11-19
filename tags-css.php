<?php
	header('Content-type: text/css');
?>
section.tags
{
	flex: 1 0 auto;
	display: flex;
	flex-direction: column;
	align-items: stretch;
	padding: 1rem 2rem;
	box-sizing: border-box;
}
section.tags > div.table-container
{
}
section.tags > div.table-container > table
{
	width: 100%;
	box-sizing: border-box;
	border-collapse: collapse;
}
section.tags > div.table-container > table tbody tr:hover
{
	background-color: #fff5ca;
}
section.tags > div.table-container > table th,
section.tags > div.table-container > table td
{
	text-align: left;
	padding: .25rem 1rem;
}
section.tags > div.table-container > table th:first-of-type,
section.tags > div.table-container > table td:first-of-type
{
	padding-left: 0rem;
}
section.tags > div.table-container > table th:last-of-type,
section.tags > div.table-container > table td:last-of-type
{
	padding-right: 0rem;
}
section.tags > div.table-container > table th.right,
section.tags > div.table-container > table td.right
{
	text-align: right;
}
section.tags > div.table-container > table th
{
	background-color: #e2e2e2;
	font-weight: 500;;
}
section.tags > div.table-container > table td
{
	border-bottom: .0625rem solid #e2e2e2;
}
section.tags > div.table-container > table td.tag-id
{
	text-align: right;
}
section.tags > div.records-count
{
	padding: 1rem;
	padding-left: 0rem;
}