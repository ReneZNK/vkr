/**
 * Открыть/закрыть боковое меню
 */

function displaySideMenu() {
	const sideMenu_Elem = document.querySelector('#view_sidemenu');
	sideMenu_Elem.classList.toggle('content__sidemenu_hidden');
}

/**
 * Закрыть окно сообщения
 */

function closeMessageBox(id) {
	const message_box = document.querySelector('#message_box__' + id);
	message_box.remove();
}

/**
 * Создание объекта XMLHTTP
 */

function getXmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function action_work_shift__queue(action) {
	var xmlhttp = getXmlHttp(); 
	xmlhttp.open('POST', 'index.php', true); 
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
	xmlhttp.send(encodeURIComponent(action) + "="); 
	xmlhttp.onreadystatechange = function() { 
		if (xmlhttp.readyState == 4) { 
			if (xmlhttp.status == 200) { 
				//var all_messages = document.getElementById("all_messages");
				//all_messages.innerHTML += xmlhttp.responseText; 
				alert(xmlhttp.responseText); 
			}
		}
    };
}

/**
 * Получение форм изменения данных
 */

function getForm(formName, userID, mutableElementID) {
	var xmlhttp = getXmlHttp(); 
	xmlhttp.open('POST', 'index.php', true); 
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
	xmlhttp.send(encodeURIComponent(formName) + "&formName=" + encodeURIComponent(formName) + "&userID=" + encodeURIComponent(userID)); 
	xmlhttp.onreadystatechange = function() { 
		if (xmlhttp.readyState == 4) { 
			if (xmlhttp.status == 200) { 
				var box = document.getElementById(mutableElementID);
				box.innerHTML = xmlhttp.responseText; 
			}
		}
	};
}

/**
 * Удаление записи
 */

function delUser(action, userID) {
	var xmlhttp = getXmlHttp(); 
	xmlhttp.open('POST', 'index.php', true); 
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
	xmlhttp.send(encodeURIComponent(action) + "&userID=" + encodeURIComponent(userID)); 
	xmlhttp.onreadystatechange = function() { 
		if (xmlhttp.readyState == 4) { 
			if (xmlhttp.status == 200) { 
				alert(xmlhttp.responseText); 
			}
		}
	};
}

if ( window.location.pathname == '/queue' )
{
	/**
	 * Обновление данных таблицы "Сотрудники на перерыве" в фоновом режиме
	 */
	
	function queueDataCheck(action, mutableElementID) {
		var xmlhttp = getXmlHttp(); 
		xmlhttp.open('POST', 'index.php', true); 
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
		xmlhttp.send(encodeURIComponent(action) + "="); 
		xmlhttp.onreadystatechange = function() { 
			if (xmlhttp.readyState == 4) { 
				if (xmlhttp.status == 200) { 
					var all_messages = document.getElementById(mutableElementID);
					all_messages.innerHTML = xmlhttp.responseText; 
				}
			}
		};
	}

	/**
	 * Отображение формы изменения данных смены
	 */

	function changeWorkShiftModal(  ) {
		getForm( 'getChangeWorkShift', null, 'form__changeWorkShift' );
	}

	//setInterval(function() { queueDataCheck('updateTableStaffBreak', 'all_staff_breaks') }, 1000);
	
	setInterval(function() { queueDataCheck('updateTableStaffBreak10Minutes', 'all_staff_breaks10') }, 1000);
	
	setInterval(function() { queueDataCheck('updateTableStaffBreak15Minutes', 'all_staff_breaks15') }, 1000);

	setInterval(function() { queueDataCheck('updateTableStaffWorkShift', 'all_staff_work_shift') }, 1000);
}

else if ( window.location.pathname == '/user' )
{
	/**
	 * Отображение формы изменения данных пользователя
	 */

	function changeUserModal( userID ) {
		getForm( 'getChangeUser', userID, userID );
	}

	/**
	 * Отображение формы регистрации пользователя
	 */

	function registerUserModal( userID ) {
		getForm( 'getRegisterUser', null, 'formRegUser' );
	}

	/**
	 * Отправка запроса на удаление пользователя
	 */

	function deleteUser( userID ) {
		delUser( 'deleteUser', userID );
	}

}