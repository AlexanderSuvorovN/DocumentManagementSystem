<?php
	header('Content-type: text/css');
?>
section.folders
{
	flex: 1 0 auto;
	display: flex;
	flex-direction: column;
	align-items: stretch;
	padding: 1rem 2rem;
	box-sizing: border-box;
}
section.folders > div.list-container
{
}
section.folders > div.list-container > table
{
	width: 100%;
	box-sizing: border-box;
	border-collapse: collapse;
}
section.folders > div.list-container > table tbody tr:hover
{
	background-color: #fff5ca;
}
section.folders > div.list-container > table th,
section.folders > div.list-container > table td
{
	text-align: left;
	padding: .25rem 1rem;
}
section.folders > div.list-container > table th:first-of-type,
section.folders > div.list-container > table td:first-of-type
{
	padding-left: 0rem;
}
section.folders > div.list-container > table th:last-of-type,
section.folders > div.list-container > table td:last-of-type
{
	padding-right: 0rem;
}
section.folders > div.list-container > table th.right,
section.folders > div.list-container > table td.right
{
	text-align: right;
}
section.folders > div.list-container > table th
{
	background-color: #e2e2e2;
	font-weight: 500;;
}
section.folders > div.list-container > table td
{
	border-bottom: .0625rem solid #e2e2e2;
}
section.folders > div.list-container > table th.id,
section.folders > div.list-container > table td.id
{
	text-align: right;
}
section.folders > div.list-container > table td.description > p
{
	margin: 0;
}
section.folders > div.list-container > div.card
{
	background-color: #fff;
	margin: 0.25rem 0rem;
	box-shadow: 0px 0px .75rem rgba(0, 0, 0, .1);
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: stretch;
	flex-wrap: nowrap;
	box-sizing: border-box;
}
section.folders > div.list-container > div.card:hover
{
	/*background-color: #fffdf2;*/
	/*cursor: pointer;*/
}
section.folders > div.list-container > div.card a.preview
{
	display: block;
	--width: 6rem;
	width: var(--width);
	height: calc(var(--width) / 0.7070);
	box-sizing: border-box;
	padding: 1rem;
}
section.folders > div.list-container > div.card a.preview:hover
{
	text-decoration: none;
}
section.folders > div.list-container > div.card a.preview div.image
{
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	background-image: none;
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
	border: none;
}
section.folders > div.list-container > div.card div.info
{
	padding: 2rem 1rem;
}
section.folders > div.list-container > div.card div.info a.label-text
{
	display: block;
	font-size: 1rem;
	font-weight: 500;
	margin-bottom: .5rem;
	color: #555;
}
section.folders > div.list-container > div.card div.info a.label-text:hover
{
	color: #333;
	text-decoration: none;
}
section.folders > div.list-container > div.card div.info a.description
{

}
section.folders > div.list-container > div.card div.info a.documents-count
{

}
section.folders > div.records-count
{
	padding: 1rem;
	padding-left: 0rem;
}