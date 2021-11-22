<?php
	header('Content-type: text/css');
	require_once("./../../common.php");
?>
@import url('https://fonts.googleapis.com/css?family=Montserrat:900');
body
{
	font-family: "Avenir LT Std 35 Light", "Roboto", sans-serif;
	font-weight: 300;
	background-color: #ebebeb;
	color: #333;
	padding: 0;
	margin: 0;
}
section
{
	padding: 1rem 1rem 1rem;
}
section > h1 
{
	font-family: "Avenir LT Std 35 Light", "Roboto", sans-serif;
	font-size: 3rem;
	font-weight: 300;
	text-align: center;
	text-transform: capitalize;
	margin: 0rem 0rem .75em;
	color: #333;
}
section.action-bar
{
	background-color: #333;
	color: white;
	--padding-vertical: .5rem;
	--button-font-size: small;
	--button-vertical-padding: .75rem;
	padding-top: var(--padding-vertical);
	padding-bottom: var(--padding-vertical);
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	position: sticky;
	top: 0;
	z-index: 9999;
}
section.action-bar > a.button.add
{
	margin-left: auto;
}
section.career-opportunities
{
}
.tabulator
{
	font-family: "Roboto", "Avenir LT Std 35 Light", sans-serif;
	font-size: small;
	font-weight: 300;
	border: none;
	background-color: #ebebeb;
}
.tabulator .tabulator-header
{
	font-weight: 500;
	border-bottom: .25rem solid #c7332f;
}
.tabulator .tabulator-header .tabulator-col
{
	border-right-color: #dcdcdc;
}
.tabulator .tabulator-header .tabulator-col:last-of-type
{
	border-right: none;
}
.tabulator .tabulator-footer
{
	border-top: .25rem solid #c7332f;	
}
.tabulator .tabulator-footer .tabulator-page-size
{
	background-color: #ebebeb;
}
.tabulator .tabulator-footer .tabulator-page
{
	font-size: small;
}
a 
{
	font-size: var(--return-font-size);
	color: #cdcdcd;
	text-decoration: none;
}
a:hover
{
	color: #e52104;
	color: white;
	text-decoration: underline;
}
a.button 
{
    display: block;
    font-family: "Avenir LT Std 65 Medium", "Roboto", sans-serif;
    font-size: var(--button-font-size);
    padding: var(--button-vertical-padding) 2rem;
    background-color: #871402;
    color: white;
    text-transform: capitalize;
    line-height: 1;
    border-radius: .25rem;
    border: .0625rem solid #666;
}
a.button:hover
{
    background-color: #e52104;
	text-decoration: none;
}
@media only screen and (max-width: 980px)
{
	section
	{	
		padding-left: 1rem;
		padding-right: 1rem;
	}
}