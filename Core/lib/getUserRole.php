<?php

function getUserRole() 
{
	return $_SESSION['logged_user']->role;
}