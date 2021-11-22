<?php
	header('Content-type: text/css');
?>
section.folder-item 
{
	flex: 1 0 auto;
	padding: 2rem calc((100% - 1140px) / 2) 2rem;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: stretch;
}
section.folder-item h1
{
	font-size: 2rem;
	margin-bottom: 0;
}
section.folder-item h2
{
	margin: 2rem 0rem 1rem;
}
section.folder-item input[type='text']
{
    font-family: "Rooto", sans-serif;
    font-weight: 300;
    font-size: small;
    background-color: #fff;
    padding: 1rem 1rem;
    box-sizing: border-box;
    width: 100%;
    border: 0.0625rem solid #c4c4c4;
    border-radius: .25rem;
	box-shadow: inset 0px 0px 3px 0px rgba(0,0,0,0.25);
    color: #333;
    margin: 0rem;
}
section.folder-item div.preview
{
	background-color: #e8e8e8;
	background-image: none;
	background-size: contain;
	background-position: left;
	background-repeat: no-repeat;
	min-height: 25vh;
	max-height: 50vh;
	box-sizing: border-box;
}
section.folder-item table.associated-documents
{
	width: 100%;
	box-sizing: border-box;
	border-collapse: collapse;
}
section.folder-item table.associated-documents th
{
	background-color: #e2e2e2;
}
section.folder-item table.associated-documents th,
section.folder-item table.associated-documents td
{
	padding: .5rem;
	text-align: left;
}
section.folder-item div.associated-documents
{
}
section.error
{
	flex: 1 0 auto;
	padding: 2.75rem calc((100% - 1200px) / 2) 2rem;
}
section.error > div.text
{
	padding: 1rem 1rem 1rem;
	background-color: #e2e2e2;
	border-radius: .25rem;
	border: .0625rem solid #c4c4c4;
	font-size: small;
}