<?php
	header('Content-type: text/css');
?>
section.tag-item 
{
	flex: 1 0 auto;
	padding: 2rem calc((100% - 1140px) / 2) 2rem;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: stretch;
}
section.tag-item h2
{
	margin: 1rem 0rem 1rem;
}
section.tag-item > table.tag-data
{
	width: 100%;
}
section.tag-item table.tag-data td.label
{
	font-size: small;
	font-weight: 500;
	text-transform: capitalize;
	padding: .5rem 0rem;
	box-sizing: border-box;
}
section.tag-item table.tag-data td.value
{
	padding: 0rem;
	box-sizing: border-box;
}
section.tag-item table.tag-data td.value input[type='text']
{
    font-family: "Rooto", sans-serif;
    font-weight: 300;
    font-size: small;
    background-color: #e2e2e2;
    border: none;
    padding: 1rem 1rem;
    box-sizing: border-box;
    width: 100%;
    border-bottom: 0.0625rem solid #cdcdcd;
    border-radius: .25rem;
    color: #333;
    margin: 0rem;
}
section.tag-item table.tag-data tr.id
{
	display: none;
}
section.tag-item table.associated-documents
{
	width: 100%;
	box-sizing: border-box;
	border-collapse: collapse;
}
section.tag-item table.associated-documents th
{
	background-color: #e2e2e2;
}
section.tag-item table.associated-documents th,
section.tag-item table.associated-documents td
{
	padding: .5rem;
	text-align: left;
}
section.tag-item div.associated-documents
{
}
section.error
{
	flex: 1 0 auto;
	padding: 2.75rem calc((100% - 1140px) / 2) 2rem;
}
section.error > div.text
{
	padding: 1rem 1rem 1rem;
	background-color: #e2e2e2;
	border-radius: .25rem;
	border: .0625rem solid #c4c4c4;
	font-size: small;
}