<?php
	header('Content-type: text/css');
?>
:root
{
	--ck-border-radius: .25rem;
	--ck-color-base-background: #ffffff;
	--ck-color-toolbar-background: #e2e2e2;
}
section.document-item 
{
	flex: 1 0 auto;
	padding: 2rem calc((100% - 1140px) / 2) 2rem;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: stretch;
}
section.document-item > input[name='document_name']
{
    font-family: "Rooto", sans-serif;
    font-weight: 700;
    font-size: 2rem;
    background-color: #e2e2e2;
    border: none;
    padding: 1.125rem 1rem .75rem;
    box-sizing: border-box;
    width: calc(100% - .25rem);
    border-bottom: 0.0625rem solid #cdcdcd;
    border-radius: .25rem;
    color: #444;
    margin: 1rem 0rem;
}
section.document-item h2
{
	margin: 1rem 0rem 1rem;
}
section.document-item > table.general-info
{
	width: 100%;
	table-layout: auto;
}
section.document-item td.label
{
	font-size: small;
	font-weight: 500;
	text-transform: capitalize;
	padding: .5rem 0rem;
	box-sizing: border-box;
}
section.document-item input[type='text']:not([name='document_name']),
section.document-item input[type='date']
{
	font-family: "Roboto", sans-serif;
	font-size: small;
	line-height: 1;
	padding: 1.125rem .75rem .75rem;
	box-sizing: border-box;
	background-color: #e2e2e2;
	border: none;
	border-bottom: .0625rem solid #cdcdcd;
	color: #444;
	border-radius: .25rem;
	width: 100%;
}
section.document-item div.ck.ck-editor
{
	font-family: "Roboto", sans-serif;
	font-weight: 300;
	font-size: small;
}
section.error
{
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