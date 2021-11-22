<?php
	header('Content-type: text/css');
?>
div.tags-collection-container
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	flex-wrap: wrap;
	box-sizing: border-box;
	padding: .5rem 0rem;
}
div.tags-collection-container div.tag
{
	padding: .5rem 1rem;
	margin-right: .5rem;
	margin-bottom: .5rem;
	box-sizing: border-box;
	background-color: #e2e2e2;
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	flex-wrap: nowrap;
}
div.tags-collection-container div.tag div.button.remove
{
	width: 1rem;
	height: 1rem;
	margin-left: .5rem;
	background-image: url('/dms/icon-remove.svg');
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
	cursor: pointer;
}