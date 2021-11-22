<?php
	header('Content-type: text/css');
?>
div.scan-container 
{
	display: flex;
	flex-direction: column;
	margin-bottom: 1rem;
}
div.scan-container div.control-bar
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: stretch;
}
div.scan-container div.control-bar div.combobox
{
	flex: 1 1 auto;
}
div.scan-container div.control-bar div.controls
{
	display: flex;
	flex-direction: row;
	border: .0625rem solid #aaa;
	background-color: #ddd;
}
div.scan-container div.control-bar div.controls div
{
	width: 1.75rem;
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
	border-left: .0625rem solid #fff;
	border-top: .0625rem solid #fff;
	border-right: .0625rem solid #aaa;
	border-bottom: .0625rem solid #aaa;
}
div.scan-container div.control-bar div.controls div.remove
{
	background-image: url('/dms/icon-remove.svg');
	cursor: pointer;
}
div.scan-container div.control-bar div.controls div.reorder
{
	background-image: url('/dms/icon-drag-reorder.svg');
	cursor: grab;
}
div.preview
{
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
	background-color: #3e3e3e;
}
embed.pdf-viewer
{
	height: 75vh;
}