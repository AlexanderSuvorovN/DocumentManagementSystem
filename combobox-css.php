<?php
	header('Content-type: text/css');
?>
div.combobox
{
	--button-width: 2rem;
	display: flex;
	justify-content: flex-start;
	align-items: stretch;
	border: .0625rem solid #aaa;
	border-radius: .25rem;
	/*overflow: hidden;*/
	position: relative;
	box-sizing: border-box;
}
div.combobox.expanded
{
	border-bottom-left-radius: 0;
	border-bottom-right-radius: 0;
}
div.combobox > input[type='text']
{
	border: none !important;
	border-radius: 0 !important;
	padding: .75rem .5rem .75rem .75rem !important;
	box-shadow: inset 0px 0px 3px 0px rgba(0,0,0,0.25);
	background-color: #fff !important;
}
div.combobox > div.button
{

	width: var(--button-width);
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	align-items: stretch;
	border-left: .0625rem solid #aaa;
	border-bottom: .0625rem solid #aaa;
	border-top: .0625rem solid #fff;
	border-top-right-radius: .25rem;
	border-bottom-right-radius: .25rem;
	/*box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.25);*/
	cursor: pointer;
}
div.combobox.expanded > div.button
{
	border-bottom-left-radius: 0;
	border-bottom-right-radius: 0;
}
div.combobox > div.button:after
{
	content: "";
	display: block;
	width: calc(var(--button-width) * .5);
	background-image: url('/dms/checkbox-arrow-down.svg');
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
}
div.combobox > div.button:hover
{
	cursor: pointer;
}
div.combobox > option
{
	display: none;
}
div.combobox > div.list
{
	position: absolute;
	width: calc(100%);
	background-color: #fff;
	border: .0625rem solid #aaa;
	left: -.0625rem;
	z-index: 10000;
	overflow-y: scroll;
	box-sizing: border-box;
}
div.combobox > div.list > div.item
{
	display: block;
	padding: .25rem .5rem .25rem .5rem;
	box-sizing: border-box;
	margin: 0;
}
div.combobox > div.list > div.item.hover
{
	background-color: #0078d7;
	color: white;
	cursor: pointer;
}
div.combobox > div.list > div.null_item
{
	font-style: italic;
	color: #888;
}