<?php

function logoutUser($_request) 
{
	unset($_SESSION['logged_user']);
	header('Location: /');
}