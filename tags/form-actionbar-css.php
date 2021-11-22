<?php
	header('Content-type: text/css');
?>
section.actionbar
{
	padding: .625rem 2rem;
}
section.actionbar > div.operation-container
{
	margin-left: auto;
	margin-right: 2rem;
	box-sizing: border-box;
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
	align-items: center;
	flex-wrap: wrap;
}
section.actionbar > div.operation-container > label
{
	font-family: "Roboto", sans-serif;
	font-size: small;
	padding-right: 1rem;
}
section.actionbar select[name='operation']
{
	font-family: "Roboto", sans-serif;
	font-size: small;
	background-color: #e2e2e2;
	border: none;
	padding-left: .25rem;
	padding-right: 3rem;
	color: #333;
	text-transform: capitalize;
	height: 1.75rem;
}
section.actionbar > a.button 
{
    padding: 0rem 2rem;
    height: 1.75rem;
}