<?php
	header('Content-type: text/css');
?>
header 
{
	padding: 1rem 2rem;
	background-color: #000;
	box-sizing: border-box;
}
header > a.caption 
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	color: #e2e2e2;
	font-size: small;
	font-weight: 500;
}
header > a.caption:before
{
	display: block;
	content: "";
	width: 1rem;
	height: 1rem;
	box-sizing: border-box;
	background-image: url('/sbc-logo-s.svg');
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
	margin-right: .5rem;
}
header > a.caption:hover
{
	text-decoration: none;
	color: white;
}