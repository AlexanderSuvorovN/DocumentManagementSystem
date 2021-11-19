<?php
	header('Content-type: text/css');
?>
section.actionbar
{
	background-color: #333;
	color: white;
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	position: sticky;
	top: 0;
	z-index: 9999;
	padding: 0rem 2rem;
	height: 3rem;
	box-sizing: border-box;
}
section.actionbar > div.breadcrumb
{
	color: #e2e2e2;
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
}
section.actionbar > div.breadcrumb > a,
section.actionbar > div.breadcrumb > span
{
	color: #e2e2e2;
	margin: 0rem .25rem;
}
section.actionbar > div.breadcrumb > a:first-of-type
{
	margin-left: 0rem;
}
section.actionbar > div.breadcrumb > a:hover
{
	color: gold;
	text-decoration: underline;
}
section.actionbar > div.breadcrumb > a.sitemap
{
	--icon-size: 1rem;
	display: inline-block;
	background-image: url('/images/icon-home-grey.svg');
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
	width: var(--icon-size);
	height: var(--icon-size);
	box-sizing: border-box;
}
section.actionbar > div.breadcrumb > a.sitemap:hover
{
	background-image: url('/images/icon-home-gold.svg');
}
section.actionbar > div.breadcrumb > span.this-page
{
	color: #e2e2e2;
}
section.actionbar > div.breadcrumb > a.return
{
	--icon-size: 1rem;
	display: inline-block;
	background-image: url('/images/icon-return-grey.svg');
	background-size: contain;
	background-position: center;
	background-repeat: none;
	width: var(--icon-size);
	height: var(--icon-size);
	box-sizing: border-box;
}
section.actionbar > div.breadcrumb > a.return:hover
{
	background-image: url('/images/icon-return-gold.svg');
}
section.actionbar > div.right
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: nowrap;
}
section.actionbar a.button
{
    font-family: "Roboto", sans-serif;
    font-size: small;
    padding: .5rem .75rem;
    /*background-color: #871402;*/
    color: white;
    text-transform: capitalize;
    line-height: 1;
    text-decoration: none;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
}
section.actionbar a.button:hover
{
    background-color: #e52104;
	text-decoration: none;
	cursor: pointer;
}
section.actionbar div.separator
{
	--line-width: .0625rem;
	border-left: var(--line-width) solid black;
	border-right: var(--line-width) solid #444;
	width: calc(var(--line-width) * 2);
	box-sizing: border-box;
	margin: 0rem .5rem;
	align-self: stretch;
}
section.actionbar a.button.view
{
	--icon-size: 1rem;
	width: var(--icon-size);
	height: var(--icon-size);
	box-sizing: border-box;
	display: block;
	margin: 0rem;
	background-image: none;
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
}
section.actionbar a.button.view:hover
{
	background-color: transparent;
}
section.actionbar a.button.view.list-type-table
{
	background-image: url('/images/icon-table-view-grey.svg');
}
section.actionbar a.button.view.list-type-table.active
{
	background-image: url('/images/icon-table-view-white.svg');
}
section.actionbar a.button.view.list-type-list
{
	background-image: url('/images/icon-list-view-grey.svg');
}
section.actionbar a.button.view.list-type-list.active
{
	background-image: url('/images/icon-list-view-white.svg');
}