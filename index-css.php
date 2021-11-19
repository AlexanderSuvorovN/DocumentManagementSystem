<?php
	header('Content-type: text/css');
?>
section.index
{
	flex: 1 0 auto;
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	padding: 1rem 2rem;
	box-sizing: border-box;
}
section.index ul
{
	margin: 0;
	list-style-type: none;
	padding-left: 0;
}
section.index > ul a
{
	display: block;
	padding: .375rem 0.5rem;
	font-weight: 300;
}
section.index a:hover
{
	background-color: #e52104;
	color: white;
	text-decoration: none;
}